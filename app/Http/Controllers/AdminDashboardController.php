<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\ReportService;

class AdminDashboardController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $kpis = $this->reportService->getDashboardKPIs();
        $revenue7Days = $this->reportService->getRevenueLast7Days();
        $revenue12Months = $this->reportService->getRevenueLast12Months();
        $ordersByStatus = $this->reportService->getOrdersByStatus();
        $topProducts = $this->reportService->getTop10BestSellers();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $lowStockProducts = Product::with(['category', 'brand'])
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'kpis',
            'revenue7Days',
            'revenue12Months',
            'ordersByStatus',
            'topProducts',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
