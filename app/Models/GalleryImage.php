<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = ['title', 'caption', 'image', 'image_url', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }

    public function getImageSrcAttribute(): string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return $this->image_url ?? 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop';
    }
}
