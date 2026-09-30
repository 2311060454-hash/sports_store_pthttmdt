<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get filtered, sorted and paginated products.
     */
    public function getFilteredProducts(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['category', 'brand', 'variants', 'reviews'])
            ->where('status', 'active');

        // Filter by Search Query
        if (!empty($filters['q'])) {
            $keyword = trim($filters['q']);
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhereHas('category', function (Builder $c) use ($keyword) {
                      $c->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('brand', function (Builder $b) use ($keyword) {
                      $b->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // Filter by Category
        if (!empty($filters['category'])) {
            $cat = $filters['category'];
            $query->whereHas('category', function (Builder $c) use ($cat) {
                if (is_numeric($cat)) {
                    $c->where('id', $cat);
                } else {
                    $c->where('slug', $cat);
                }
            });
        }

        // Filter by Brand
        if (!empty($filters['brand'])) {
            $brand = $filters['brand'];
            $query->whereHas('brand', function (Builder $b) use ($brand) {
                if (is_numeric($brand)) {
                    $b->where('id', $brand);
                } else {
                    $b->where('slug', $brand);
                }
            });
        }

        // Filter by Price range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $min = (float) $filters['min_price'];
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$min]);
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $max = (float) $filters['max_price'];
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$max]);
        }

        // Filter by Size (variant)
        if (!empty($filters['size'])) {
            $size = $filters['size'];
            $query->whereHas('variants', function (Builder $v) use ($size) {
                $v->where('size', $size);
            });
        }

        // Filter by Color (variant)
        if (!empty($filters['color'])) {
            $color = $filters['color'];
            $query->whereHas('variants', function (Builder $v) use ($color) {
                $v->where('color', $color);
            });
        }

        // Filter by Rating
        if (!empty($filters['rating']) && is_numeric($filters['rating'])) {
            $minRating = (int) $filters['rating'];
            $query->whereHas('reviews', function (Builder $r) {
                $r->where('status', 'active');
            }, '>=', 1)->withAvg(['reviews as avg_rating' => function ($r) {
                $r->where('status', 'active');
            }], 'rating')->having('avg_rating', '>=', $minRating);
        }

        // Filter on_sale
        if (!empty($filters['on_sale'])) {
            $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price');
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
            'bestseller' => $query->orderBy('sold', 'desc'),
            'rating' => $query->withAvg(['reviews as avg_rating' => function ($r) {
                $r->where('status', 'active');
            }], 'rating')->orderByDesc('avg_rating'),
            default => $query->latest('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }
}
