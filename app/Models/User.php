<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active'         => 'boolean',
    ];

    // ── Helper Role ──────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isAdminOnly(): bool
    {
        return $this->role === 'admin';
    }

    // ── Permission ───────────────────────────────────────────────────────

    /**
     * Super Admin : akses penuh ke semua fitur (konten, laporan, pengaturan,
     *               activity log, & manajemen user).
     * Admin resto : DIBATASI pada operasional — kelola menu/produk, melihat
     *               transaksi/reservasi, & approval status pembayaran.
     * Pelanggan   : tidak ada akses admin.
     */
    public function hasPermission(string $permission): bool
    {
        // Super Admin selalu punya semua permission
        if ($this->role === 'super_admin') {
            return true;
        }

        $permissions = [
            'admin' => [
                'view_dashboard',
                'edit_menu',          // tambah / edit produk
                'delete_data',        // hapus produk (menu)
                'view_transactions',  // lihat reservasi / transaksi
                'approve_payment',    // approval lunas / belum lunas
            ],
            'pelanggan' => [],
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }

    // ── Scope ────────────────────────────────────────────────────────────

    public function scopeSuperAdmins($query)
    {
        return $query->where('role', 'super_admin');
    }

    public function scopeAdmins($query)
    {
        return $query->whereIn('role', ['admin', 'super_admin']);
    }

    public function scopePelanggan($query)
    {
        return $query->where('role', 'pelanggan');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ── Label Role ───────────────────────────────────────────────────────

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'pelanggan'   => 'Pelanggan',
            default       => ucfirst($this->role),
        };
    }

    public function getRoleBadgeColorAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'bg-purple-100 text-purple-700',
            'admin'       => 'bg-blue-100 text-blue-700',
            default       => 'bg-gray-100 text-gray-600',
        };
    }

    // ── Avatar ───────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        // Gravatar fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=80";
    }
}
