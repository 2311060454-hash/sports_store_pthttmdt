<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        $filter = $request->input('filter', 'all');
        if ($filter === 'low_stock') {
            $query->where('quantity', '>', 0)->where('quantity', '<=', 5);
        } elseif ($filter === 'out_of_stock') {
            $query->where('quantity', 0);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->orderBy('quantity', 'asc')->paginate(15)->withQueryString();

        $lowStockCount = Product::where('quantity', '>', 0)->where('quantity', '<=', 5)->count();
        $outOfStockCount = Product::where('quantity', 0)->count();
        $inStockCount = Product::where('quantity', '>', 5)->count();

        return view('admin.inventory.index', compact(
            'products',
            'filter',
            'lowStockCount',
            'outOfStockCount',
            'inStockCount'
        ));
    }

    public function quickUpdate(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $product->update([
            'quantity' => $request->quantity,
        ]);

        return back()->with('success', 'Đã cập nhật số lượng tồn kho cho sản phẩm ' . $product->name . ' thành ' . $request->quantity . '.');
    }
}
