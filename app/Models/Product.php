<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'sale_price',
        'quantity',
        'thumbnail',
        'status',
        'featured',
        'views',
        'sold',
    ];

    protected $casts = [
        'price' => 'float',
        'sale_price' => 'float',
        'quantity' => 'integer',
        'featured' => 'boolean',
        'views' => 'integer',
        'sold' => 'integer',
    ];

    protected $appends = ['final_price', 'discount_percent', 'is_in_stock', 'average_rating'];

    public function getFinalPriceAttribute(): float
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (float) $this->sale_price;
        }
        return (float) $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->sale_price !== null && $this->sale_price > 0 && $this->sale_price < $this->price) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    public function getAverageRatingAttribute(): float
    {
        $avg = $this->reviews()->where('status', 'active')->avg('rating');
        return $avg ? round($avg, 1) : 5.0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'active')->latest();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
