<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'approved_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Helper ───────────────────────────────────────────────────────────

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'cuti_tahunan' => '🏖️ Cuti Tahunan',
            'sakit'        => '🤒 Sakit',
            'izin'         => '📋 Izin',
            'cuti_khusus'  => '⭐ Cuti Khusus',
            default        => $this->type,
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'menunggu'  => ['label' => 'Menunggu',  'class' => 'bg-yellow-100 text-yellow-700'],
            'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700'],
            'ditolak'   => ['label' => 'Ditolak',   'class' => 'bg-red-100 text-red-700'],
            default     => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-700'],
        };
    }
}
