<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IntegrationController extends Controller
{
    /**
     * Handle MoMo redirect callback (GET).
     */
    public function momoCallback(Request $request): RedirectResponse
    {
        Log::info('MoMo Callback Received', $request->all());

        $orderCode = $request->input('orderId');
        $resultCode = $request->input('resultCode');

        if ($orderCode) {
            $order = Order::where('order_code', $orderCode)->first();
            if ($order && (string) $resultCode === '0') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
                ]);
                return redirect()->route('checkout.success', $order->order_code)
                    ->with('success', 'Thanh toán MoMo thành công!');
            }
        }

        return redirect()->route('account.orders')
            ->with('info', 'Thông báo MoMo: ' . ($request->input('message') ?? 'Giao dịch đã được ghi nhận.'));
    }

    /**
     * Handle MoMo IPN notification (POST).
     */
    public function momoIpn(Request $request): JsonResponse
    {
        Log::info('MoMo IPN Received', $request->all());

        $orderCode = $request->input('orderId');
        $resultCode = $request->input('resultCode');

        if ($orderCode) {
            $order = Order::where('order_code', $orderCode)->first();
            if ($order && (string) $resultCode === '0') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
                ]);
            }
        }

        return response()->json([
            'resultCode' => 0,
            'message' => 'IPN received and processed successfully',
        ]);
    }

    /**
     * Handle VNPay payment return callback (GET).
     */
    public function vnpayCallback(Request $request): RedirectResponse
    {
        Log::info('VNPay Callback Received', $request->all());

        $orderCode = $request->input('vnp_TxnRef');
        $responseCode = $request->input('vnp_ResponseCode');

        if ($orderCode) {
            $order = Order::where('order_code', $orderCode)->first();
            if ($order && $responseCode === '00') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
                ]);
                return redirect()->route('checkout.success', $order->order_code)
                    ->with('success', 'Thanh toán VNPay thành công!');
            }
        }

        return redirect()->route('account.orders')
            ->with('info', 'Giao dịch VNPay hoàn tất với mã phản hồi: ' . $responseCode);
    }

    /**
     * Handle Giao Hang Nhanh (GHN) webhook (POST).
     */
    public function ghnWebhook(Request $request): JsonResponse
    {
        Log::info('GHN Webhook Received', $request->all());

        $orderCode = $request->input('client_order_code') ?? $request->input('order_code');
        $status = strtolower((string) $request->input('status'));

        if ($orderCode) {
            $order = Order::where('order_code', $orderCode)->first();
            if ($order) {
                // Map GHN status to system status
                $mappedStatus = match ($status) {
                    'delivering', 'picked' => 'shipping',
                    'delivered' => 'completed',
                    'cancel' => 'cancelled',
                    default => null,
                };

                if ($mappedStatus) {
                    $updateData = ['status' => $mappedStatus];
                    if ($mappedStatus === 'completed') {
                        $updateData['payment_status'] = 'paid';
                    }
                    $order->update($updateData);
                }
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'GHN Webhook processed successfully',
        ]);
    }
}
