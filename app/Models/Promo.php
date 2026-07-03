<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model {
    protected $fillable = ['title','description','code','discount_type','discount_value','min_purchase','icon','badge','gradient','expiry_label','valid_until','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean', 'valid_until' => 'date'];

    public function getDiscountLabelAttribute(): string {
        if ($this->discount_type === 'percent') return "Diskon {$this->discount_value}%";
        return 'Potongan Rp '.number_format($this->discount_value, 0, ',', '.');
    }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
