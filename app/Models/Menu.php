<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'category', 'price', 'original_price',
        'image', 'image_url', 'label', 'is_stock', 'is_promo',
        'is_new', 'is_active', 'sold_count', 'rating', 'review_count', 'sort_order',
    ];

    protected $appends = ['image_src'];

    protected $casts = [
        'is_stock'  => 'boolean',
        'is_promo'  => 'boolean',
        'is_new'    => 'boolean',
        'is_active' => 'boolean',
        'price'     => 'integer',
        'original_price' => 'integer',
        'rating'    => 'float',
    ];

    // Gambar: prioritaskan file upload, fallback ke URL
    public function getImageSrcAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        if ($this->image_url) {
            return $this->image_url;
        }
        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=300&fit=crop';
    }

    // Scope aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope per kategori
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Format harga
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
