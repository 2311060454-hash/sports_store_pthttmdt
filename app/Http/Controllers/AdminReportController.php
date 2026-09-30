<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $kpis = $this->reportService->getDashboardKPIs();
        $revenue7Days = $this->reportService->getRevenueLast7Days();
        $revenue12Months = $this->reportService->getRevenueLast12Months();
        $ordersByStatus = $this->reportService->getOrdersByStatus();

        // Revenue by Category
        $categoryRevenue = Category::join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        return view('admin.reports.index', compact(
            'kpis',
            'revenue7Days',
            'revenue12Months',
            'ordersByStatus',
            'categoryRevenue'
        ));
    }
}
