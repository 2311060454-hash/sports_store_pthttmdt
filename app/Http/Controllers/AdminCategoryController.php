<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . trim($request->q) . '%');
        }

        $categories = $query->latest()->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $this->imageService->upload($request->file('image'), 'categories');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục mới thành công!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->only(['name', 'description', 'status']);
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $this->imageService->delete($category->image);
            $data['image'] = $this->imageService->upload($request->file('image'), 'categories');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm. Vui lòng chuyển hoặc xóa sản phẩm trước.');
        }

        $this->imageService->delete($category->image);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
