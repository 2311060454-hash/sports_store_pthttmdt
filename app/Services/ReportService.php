<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getDashboardKPIs(): array
    {
        $today = Carbon::today();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $todayRevenue = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $lowStockProducts = Product::where('quantity', '<=', 5)->count();
        $outOfStockProducts = Product::where('quantity', 0)->count();

        return [
            'total_revenue' => (float) $totalRevenue,
            'today_revenue' => (float) $todayRevenue,
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'total_products' => $totalProducts,
            'total_customers' => $totalCustomers,
            'low_stock_products' => $lowStockProducts,
            'out_of_stock_products' => $outOfStockProducts,
        ];
    }

    public function getRevenueLast7Days(): array
    {
        $days = [];
        $revenues = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStr = $date->format('Y-m-d');
            $label = $date->format('d/m');

            $rev = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $dayStr)
                ->sum('total_amount');

            $days[] = $label;
            $revenues[] = (float) $rev;
        }

        return [
            'labels' => $days,
            'data' => $revenues,
        ];
    }

    public function getRevenueLast12Months(): array
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            $label = 'T' . $month . '/' . $date->format('y');

            $rev = Order::where('status', '!=', 'cancelled')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('total_amount');

            $months[] = $label;
            $revenues[] = (float) $rev;
        }

        return [
            'labels' => $months,
            'data' => $revenues,
        ];
    }

    public function getOrdersByStatus(): array
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'preparing' => 'Chuẩn bị hàng',
            'shipping' => 'Đang giao',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $labels = [];
        $counts = [];

        foreach ($statuses as $key => $label) {
            $labels[] = $label;
            $counts[] = Order::where('status', $key)->count();
        }

        return [
            'labels' => $labels,
            'data' => $counts,
        ];
    }

    public function getTop10BestSellers()
    {
        return Product::with(['category', 'brand'])
            ->orderBy('sold', 'desc')
            ->take(10)
            ->get();
    }
}
