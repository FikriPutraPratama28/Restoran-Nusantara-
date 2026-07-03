<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_code',
        'reservation_date',
        'reservation_time',
        'number_of_guests',
        'customer_name',
        'customer_phone',
        'customer_email',
        'notes',
        'table_area',
        'table_number',
        'status',
        'payment_method',
        'ordered_items',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'ordered_items' => 'array',
    ];

    /**
     * Generate a unique reservation code
     */
    public static function generateCode()
    {
        do {
            $code = 'RES-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (static::where('reservation_code', $code)->exists());

        return $code;
    }

    // Accessor untuk hitung total harga pesanan secara dinamis
    public function getTotalPriceAttribute(): int
    {
        if (empty($this->ordered_items) || !is_array($this->ordered_items)) {
            return 0;
        }

        return array_reduce($this->ordered_items, function ($carry, $item) {
            $price = isset($item['price']) ? (int) $item['price'] : 0;
            $qty = isset($item['qty']) ? (int) $item['qty'] : 1;
            return $carry + ($price * $qty);
        }, 0);
    }

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk filter status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->toDateString())
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->orderBy('reservation_date');
    }
}
