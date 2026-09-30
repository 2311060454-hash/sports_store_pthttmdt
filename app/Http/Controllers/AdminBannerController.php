<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\ImageService;
use Illuminate\Http\Request;

class AdminBannerController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề banner.',
            'image.required' => 'Vui lòng chọn hình ảnh banner.',
        ]);

        $data = $request->only(['title', 'link', 'position', 'status']);
        $data['image'] = $this->imageService->upload($request->file('image'), 'banners');

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner mới thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'link' => 'nullable|string|max:255',
            'position' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['title', 'link', 'position', 'status']);

        if ($request->hasFile('image')) {
            $this->imageService->delete($banner->image);
            $data['image'] = $this->imageService->upload($request->file('image'), 'banners');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        $this->imageService->delete($banner->image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Xóa banner thành công!');
    }
}
