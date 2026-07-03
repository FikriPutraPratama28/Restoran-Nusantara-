<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model {
    protected $fillable = ['title','description','icon','tag','image','image_url','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function getImageSrcAttribute(): string {
        if ($this->image) return asset('storage/'.$this->image);
        return $this->image_url ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop';
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
