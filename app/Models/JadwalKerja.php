<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    protected $table = 'jadwal_kerja';

    protected $fillable = [
        'user_id',
        'shift',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Default jam per shift ─────────────────────────────────────────────

    public static array $shiftDefaults = [
        'pagi'  => ['mulai' => '06:00', 'selesai' => '14:00'],
        'siang' => ['mulai' => '14:00', 'selesai' => '22:00'],
        'malam' => ['mulai' => '22:00', 'selesai' => '06:00'],
        'full'  => ['mulai' => '08:00', 'selesai' => '17:00'],
    ];

    // ── Relasi ────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessor ──────────────────────────────────────────────────────────

    public function getJamMulaiDisplayAttribute(): string
    {
        return $this->jam_mulai
            ? substr($this->jam_mulai, 0, 5)
            : (static::$shiftDefaults[$this->shift]['mulai'] ?? '--:--');
    }

    public function getJamSelesaiDisplayAttribute(): string
    {
        return $this->jam_selesai
            ? substr($this->jam_selesai, 0, 5)
            : (static::$shiftDefaults[$this->shift]['selesai'] ?? '--:--');
    }

    public function getHariLabelAttribute(): string
    {
        return match($this->hari) {
            'senin'   => 'Senin',
            'selasa'  => 'Selasa',
            'rabu'    => 'Rabu',
            'kamis'   => 'Kamis',
            'jumat'   => 'Jumat',
            'sabtu'   => 'Sabtu',
            'minggu'  => 'Minggu',
            default   => ucfirst($this->hari),
        };
    }

    public function getShiftLabelAttribute(): string
    {
        return match($this->shift) {
            'pagi'  => '🌅 Pagi',
            'siang' => '☀️ Siang',
            'malam' => '🌙 Malam',
            'full'  => '🕐 Full Day',
            default => ucfirst($this->shift),
        };
    }
}
