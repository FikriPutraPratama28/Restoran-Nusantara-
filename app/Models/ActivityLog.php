<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'actor', 'role', 'action', 'module',
        'description', 'subject_type', 'subject_id',
        'properties', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // ── Scopes ───────────────────────────────────────────────────────────

    public function scopeForModule(Builder $q, string $module): Builder
    {
        return $q->where('module', $module);
    }

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeForRole(Builder $q, string $role): Builder
    {
        return $q->where('role', $role);
    }

    // ── Static Logger ────────────────────────────────────────────────────

    /**
     * Catat aktivitas.
     *
     * @param string      $action      Kode aksi: create_menu, update_payment_status, dll
     * @param string      $module      Modul: Menu, Reservation, Content, Auth
     * @param string      $description Kalimat deskriptif
     * @param Model|null  $subject     Model yang terlibat (opsional)
     * @param array       $properties  Data tambahan (opsional)
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?Model $subject = null,
        array $properties = []
    ): void {
        try {
            $user   = Auth::user();
            $userId = $user?->id;
            $actor  = $user?->name ?? session('admin_name', 'System');
            $role   = $user?->role ?? (session('admin_logged_in') ? 'admin' : 'system');

            static::create([
                'user_id'      => $userId,
                'actor'        => $actor,
                'role'         => $role,
                'action'       => $action,
                'module'       => $module,
                'description'  => $description,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->id,
                'properties'   => empty($properties) ? null : $properties,
                'ip_address'   => Request::ip(),
                'user_agent'   => substr(Request::userAgent() ?? '', 0, 255),
            ]);
        } catch (\Throwable $e) {
            // Jangan sampai log error menghentikan request utama
            \Illuminate\Support\Facades\Log::warning('ActivityLog::log failed: ' . $e->getMessage());
        }
    }

    // ── Icon & Color helper ───────────────────────────────────────────────

    public function getIconAttribute(): string
    {
        return match($this->action) {
            'login'                     => 'login',
            'logout'                    => 'logout',
            'create_menu'               => 'create',
            'update_menu'               => 'edit',
            'delete_menu'               => 'delete',
            'toggle_stock'              => 'stock',
            'create_promo'              => 'promo',
            'update_promo'              => 'edit',
            'delete_promo'              => 'delete',
            'update_hero'               => 'image',
            'update_about'              => 'document',
            'update_reservation_status' => 'edit',
            'update_payment_status'     => 'approve',
            'register'                  => 'register',
            default                     => 'log',
        };
    }

    public function getColorAttribute(): string
    {
        return match($this->module) {
            'Auth'        => 'bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-400',
            'Menu'        => 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
            'Reservation' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400',
            'Content'     => 'bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400',
            default       => 'bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300',
        };
    }
}
