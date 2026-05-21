<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model {
    protected $fillable = ['title','subtitle','description_1','description_2','image','image_url','stats','is_active'];
    protected $casts = ['is_active' => 'boolean', 'stats' => 'array'];

    public function getImageSrcAttribute(): string {
        if ($this->image) return asset('storage/'.$this->image);
        return $this->image_url ?? 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=500&fit=crop';
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
