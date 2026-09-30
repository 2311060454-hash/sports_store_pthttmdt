<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total_amount');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->loadCount('orders');
        $orders = $customer->orders()->latest()->paginate(10);

        return view('admin.customers.show', compact('customer', 'orders'));
    }

    public function toggleStatus(User $customer)
    {
        if ($customer->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản quản trị của chính mình.');
        }

        $newStatus = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->update(['status' => $newStatus]);

        $msg = $newStatus === 'active' ? 'Đã mở khóa tài khoản khách hàng.' : 'Đã khóa tài khoản khách hàng.';
        return back()->with('success', $msg);
    }
}
