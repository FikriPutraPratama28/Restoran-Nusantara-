<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model {
    protected $fillable = ['name','role','image','image_url','emoji','gradient','bio','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];

    public function getImageSrcAttribute(): ?string {
        if ($this->image) return asset('storage/'.$this->image);
        return $this->image_url ?: null;
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
