<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'jabatan',
        'shift',
        'join_date',
        'address',
        'emergency_contact',
        'status',
        'salary',
        'notes',
    ];

    protected $casts = [
        'join_date' => 'date',
        'salary'    => 'decimal:2',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // ── Helper ───────────────────────────────────────────────────────────

    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }

    public function monthAttendances(int $month = null, int $year = null)
    {
        $month = $month ?? now()->month;
        $year  = $year  ?? now()->year;
        return $this->attendances()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();
    }

    public function getShiftLabelAttribute(): string
    {
        return match($this->shift) {
            'pagi'  => '🌅 Pagi (06:00–14:00)',
            'siang' => '☀️ Siang (14:00–22:00)',
            'malam' => '🌙 Malam (22:00–06:00)',
            'full'  => '🕐 Full Day (08:00–17:00)',
            default => $this->shift,
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'aktif'    => ['label' => 'Aktif',    'class' => 'bg-emerald-100 text-emerald-700'],
            'cuti'     => ['label' => 'Cuti',     'class' => 'bg-yellow-100 text-yellow-700'],
            'nonaktif' => ['label' => 'Nonaktif', 'class' => 'bg-red-100 text-red-700'],
            default    => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-700'],
        };
    }

    // Generate kode karyawan otomatis
    public static function generateCode(): string
    {
        $last = static::orderBy('id', 'desc')->first();
        $num  = $last ? (intval(substr($last->employee_code, 3)) + 1) : 1;
        return 'EMP' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
