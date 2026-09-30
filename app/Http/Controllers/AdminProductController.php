<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants']);

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out_of_stock') {
                $query->where('quantity', 0);
            } elseif ($request->stock_status === 'low_stock') {
                $query->where('quantity', '>', 0)->where('quantity', '<=', 5);
            } elseif ($request->stock_status === 'in_stock') {
                $query->where('quantity', '>', 5);
            }
        }

        $products = $query->latest('id')->paginate(12)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(StoreProductRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->only([
                'category_id',
                'brand_id',
                'name',
                'sku',
                'price',
                'sale_price',
                'quantity',
                'short_description',
                'description',
                'status',
            ]);

            $data['slug'] = Str::slug($request->name) . '-' . Str::random(5);
            $data['featured'] = $request->boolean('featured');

            // Handle primary thumbnail
            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = $this->imageService->upload($request->file('thumbnail'), 'products');
            }

            $product = Product::create($data);

            // Handle gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imgUrl = $this->imageService->upload($file, 'products/gallery');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imgUrl,
                    ]);
                }
            }

            // Handle variants
            if ($request->filled('variants') && is_array($request->variants)) {
                foreach ($request->variants as $var) {
                    if (!empty($var['size']) || !empty($var['color'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $var['size'] ?? null,
                            'color' => $var['color'] ?? null,
                            'sku' => $var['sku'] ?? ($product->sku . '-' . Str::upper(Str::random(4))),
                            'quantity' => (int) ($var['quantity'] ?? 0),
                            'additional_price' => (float) ($var['additional_price'] ?? 0),
                        ]);
                    }
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm mới thành công!');
        });
    }

    public function edit(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'variants']);
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $data = $request->only([
                'category_id',
                'brand_id',
                'name',
                'sku',
                'price',
                'sale_price',
                'quantity',
                'short_description',
                'description',
                'status',
            ]);

            $data['featured'] = $request->boolean('featured');

            if ($request->hasFile('thumbnail')) {
                $this->imageService->delete($product->thumbnail);
                $data['thumbnail'] = $this->imageService->upload($request->file('thumbnail'), 'products');
            }

            $product->update($data);

            // Append new gallery images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $imgUrl = $this->imageService->upload($file, 'products/gallery');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imgUrl,
                    ]);
                }
            }

            // Sync variants: simple and clean
            if ($request->has('variants') && is_array($request->variants)) {
                $submittedVariantIds = [];

                foreach ($request->variants as $var) {
                    if (!empty($var['size']) || !empty($var['color'])) {
                        if (!empty($var['id'])) {
                            $existing = ProductVariant::where('product_id', $product->id)->find($var['id']);
                            if ($existing) {
                                $existing->update([
                                    'size' => $var['size'] ?? null,
                                    'color' => $var['color'] ?? null,
                                    'sku' => $var['sku'] ?? $existing->sku,
                                    'quantity' => (int) ($var['quantity'] ?? 0),
                                    'additional_price' => (float) ($var['additional_price'] ?? 0),
                                ]);
                                $submittedVariantIds[] = $existing->id;
                            }
                        } else {
                            $created = ProductVariant::create([
                                'product_id' => $product->id,
                                'size' => $var['size'] ?? null,
                                'color' => $var['color'] ?? null,
                                'sku' => $var['sku'] ?? ($product->sku . '-' . Str::upper(Str::random(4))),
                                'quantity' => (int) ($var['quantity'] ?? 0),
                                'additional_price' => (float) ($var['additional_price'] ?? 0),
                            ]);
                            $submittedVariantIds[] = $created->id;
                        }
                    }
                }

                // Delete variants removed by user
                ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $submittedVariantIds)
                    ->delete();
            }

            return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
        });
    }

    public function destroy(Product $product)
    {
        // Delete thumbnail
        $this->imageService->delete($product->thumbnail);

        // Delete gallery images
        foreach ($product->images as $img) {
            $this->imageService->delete($img->image);
            $img->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function deleteImage(ProductImage $image)
    {
        $this->imageService->delete($image->image);
        $image->delete();

        return back()->with('success', 'Đã xóa ảnh chi tiết sản phẩm.');
    }
}
