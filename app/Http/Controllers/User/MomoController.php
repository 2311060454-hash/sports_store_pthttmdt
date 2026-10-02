<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\GHNOrderService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    /**
     * 1. start() — bắt đầu thanh toán đơn hàng bằng MoMo
     */
    public function start(Order $order, MomoService $momo)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    /**
     * 2. payAgain() — thanh toán lại đơn hàng cũ chưa thanh toán
     */
    public function payAgain(Order $order, MomoService $momo)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return $this->redirectToMomo($order, $this->newTransaction($order), $momo);
    }

    /**
     * 5. callback() — xử lý khi khách thanh toán xong quay về website
     */
    public function callback(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo callback received', [
            'payload' => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if (!$momo->isValidSuccessfulResponse($request->all())) {
            Log::warning('MoMo callback rejected', [
                'result_code' => $request->input('resultCode'),
                'order_id' => $request->input('orderId'),
                'signature_valid' => $momo->isValidResponse($request->all()),
            ]);

            if ($momo->isValidResponse($request->all())) {
                $this->markFailed($request->all(), $momo);
            }

            $orderId = $momo->orderId($request->all());
            if ($orderId) {
                return redirect()->route('account.orders.show', $orderId)
                    ->with('error', 'Giao dịch MoMo không thành công hoặc đã bị hủy. Bạn có thể nhấn "Thanh toán lại" bên dưới.');
            }

            return redirect()->route('account.orders')->with('error', 'Giao dịch MoMo thất bại.');
        }

        $result = $this->completePayment($request->all(), $ghnOrders, $momo);
        $message = in_array($result, ['created', 'already_created'], true)
            ? 'Thanh toán MoMo thành công! Vận đơn GHN đã được khởi tạo.'
            : 'Thanh toán thành công! Đơn hàng đang chờ tạo vận đơn GHN.';

        $orderId = $momo->orderId($request->all());
        if ($orderId) {
            return redirect()->route('account.orders.show', $orderId)
                ->with('success', $message);
        }

        return redirect()->route('account.orders')->with('success', $message);
    }

    /**
     * 6. ipn() — nhận thông báo trực tiếp từ máy chủ MoMo (Server-to-Server)
     */
    public function ipn(Request $request, GHNOrderService $ghnOrders, MomoService $momo)
    {
        Log::info('MoMo IPN received', [
            'payload' => $request->except('signature'),
            'has_signature' => $request->has('signature'),
        ]);

        if ($momo->isValidSuccessfulResponse($request->all())) {
            $this->completePayment($request->all(), $ghnOrders, $momo);
        } elseif ($momo->isValidResponse($request->all())) {
            $this->markFailed($request->all(), $momo);
        }

        return response()->json(['message' => 'Received', 'resultCode' => 0]);
    }

    /**
     * 3. newTransaction() — lưu một lần thử thanh toán
     */
    private function newTransaction(Order $order): PaymentTransaction
    {
        return PaymentTransaction::create([
            'order_id' => $order->id,
            'gateway' => 'momo',
            'amount' => $order->total_amount ?? $order->total_price,
            'status' => 'pending',
        ]);
    }

    /**
     * 4. redirectToMomo() — gọi API MoMo và chuyển hướng người dùng đến trang thanh toán
     */
    private function redirectToMomo(Order $order, PaymentTransaction $transaction, MomoService $momo)
    {
        $result = $momo->createPayment($order, $transaction);

        return isset($result['payUrl'])
            ? redirect($result['payUrl'])
            : redirect()->route('account.orders.show', $order)->with('error', 'Không thể kết nối tới MoMo: ' . ($result['message'] ?? 'Vui lòng thử lại.'));
    }

    /**
     * 7. completePayment() — xác nhận thanh toán thành công và tạo vận đơn GHN
     */
    private function completePayment(array $payload, GHNOrderService $ghnOrders, MomoService $momo): string
    {
        $result = DB::transaction(function () use ($payload, $momo) {
            $transaction = PaymentTransaction::where('gateway', 'momo')
                ->where('gateway_order_id', $payload['orderId'] ?? '')
                ->lockForUpdate()
                ->first();

            if (!$transaction) {
                return 'invalid';
            }

            $order = Order::lockForUpdate()->find($transaction->order_id);

            if (!$order) {
                return 'invalid';
            }

            if ($order->ghn_order_code && $order->payment_status === 'paid') {
                return 'already_created';
            }

            if ($order->shipping_status === 'processing') {
                return 'processing';
            }

            if ((int) $transaction->amount !== (int) ($payload['amount'] ?? 0)) {
                $momo->markFailed($transaction, $payload);
                return 'invalid';
            }

            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'shipping_status' => 'processing',
            ]);

            $momo->markPaid($transaction, $payload);

            return ['create', $order->id];
        });

        if (!is_array($result)) {
            return (string) $result;
        }

        $order = Order::with('items.product')->find($result[1]);
        $response = $ghnOrders->create($order, true); // isPaid = true => cod_amount = 0

        if (isset($response['code']) && $response['code'] == 200) {
            $order->update([
                'ghn_order_code' => $response['data']['order_code'],
                'shipping_status' => 'ready_to_pick',
            ]);

            try {
                if (!empty($order->customer_email)) {
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmationMail($order));
                }
            } catch (\Throwable $e) {
                Log::warning('MoMo order email sending error: ' . $e->getMessage());
            }

            return 'created';
        }

        Log::error('GHN order failed after MoMo payment', [
            'order_id' => $order->id,
            'response' => $response,
        ]);

        $order->update(['shipping_status' => 'pending']);

        try {
            if (!empty($order->customer_email)) {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmationMail($order));
            }
        } catch (\Throwable $e) {
            Log::warning('MoMo order email sending error: ' . $e->getMessage());
        }

        return 'failed';
    }

    /**
     * 8. markFailed() — ghi nhận giao dịch thất bại
     */
    private function markFailed(array $payload, MomoService $momo): void
    {
        $transaction = PaymentTransaction::where('gateway', 'momo')
            ->where('gateway_order_id', $payload['orderId'] ?? '')
            ->first();

        if ($transaction && $transaction->status !== 'paid') {
            $momo->markFailed($transaction, $payload);
        }
    }
}
