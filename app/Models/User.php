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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Permission ───────────────────────────────────────────────────────

    /**
     * Daftar permission per role.
     * Admin  : akses penuh ke semua fitur.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = [
            'admin' => [
                'delete_data',
                'edit_menu',
                'edit_content',
                'view_reports',
                'view_dashboard',
                'view_activity_log',
                'clear_activity_log',
            ],
            'pelanggan' => [],
        ];

        return in_array($permission, $permissions[$this->role] ?? []);
    }

    // ── Scope ────────────────────────────────────────────────────────────

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopePelanggan($query)
    {
        return $query->where('role', 'pelanggan');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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
