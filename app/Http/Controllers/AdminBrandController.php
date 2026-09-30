<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . trim($request->q) . '%');
        }

        $brands = $query->latest()->paginate(10)->withQueryString();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->imageService->upload($request->file('logo'), 'brands');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu mới thành công!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('logo')) {
            $this->imageService->delete($brand->logo);
            $data['logo'] = $this->imageService->upload($request->file('logo'), 'brands');
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa thương hiệu đang có sản phẩm liên kết.');
        }

        $this->imageService->delete($brand->logo);
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Xóa thương hiệu thành công!');
    }
}
