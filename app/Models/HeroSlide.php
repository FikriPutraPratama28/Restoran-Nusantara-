<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model {
    protected $fillable = ['title','subtitle','description','cta_text','cta_link','image','image_url','media_type','video_url','overlay_color','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function getImageSrcAttribute(): string {
        if ($this->image) return asset('storage/'.$this->image);
        return $this->image_url ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1920&h=1080&fit=crop';
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
