<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'late_minutes',
        'check_in_photo',
        'check_out_photo',
        'check_in_lat',
        'check_in_lng',
        'check_out_lat',
        'check_out_lng',
        'qr_token',
        'notes',
    ];

    protected $casts = [
        'date'          => 'date',
        'late_minutes'  => 'integer',
        'check_in_lat'  => 'float',
        'check_in_lng'  => 'float',
        'check_out_lat' => 'float',
        'check_out_lng' => 'float',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // ── Helper ───────────────────────────────────────────────────────────

    public function getWorkDurationAttribute(): ?string
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }
        $in  = Carbon::parse($this->check_in);
        $out = Carbon::parse($this->check_out);
        $diff = $in->diff($out);
        return $diff->h . 'j ' . $diff->i . 'm';
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'hadir'    => ['label' => 'Hadir',    'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'],
            'terlambat'=> ['label' => 'Terlambat','class' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'],
            'izin'     => ['label' => 'Izin',     'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'],
            'sakit'    => ['label' => 'Sakit',    'class' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'],
            'alpha'    => ['label' => 'Alpha',    'class' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'],
            default    => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-700'],
        };
    }

    public function getCheckInPhotoUrlAttribute(): ?string
    {
        return $this->check_in_photo ? asset('storage/' . $this->check_in_photo) : null;
    }

    public function getCheckOutPhotoUrlAttribute(): ?string
    {
        return $this->check_out_photo ? asset('storage/' . $this->check_out_photo) : null;
    }

    // Batas jam masuk (terlambat jika lewat jam ini)
    public static function lateThreshold(): string
    {
        return '08:30:00';
    }
}
