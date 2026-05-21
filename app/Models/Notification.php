<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'message',
        'icon', 'color', 'url',
        'notifiable_type', 'notifiable_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    // ── Scopes ───────────────────────────────────────────────────────────

    public function scopeUnread(Builder $q): Builder
    {
        return $q->whereNull('read_at');
    }

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    // ── Helpers ──────────────────────────────────────────────────────────

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    // ── Static Factory ───────────────────────────────────────────────────

    /**
     * Kirim notifikasi ke satu atau banyak user.
     */
    public static function send(
        int|array $userIds,
        string $type,
        string $title,
        string $message,
        string $icon = '🔔',
        string $color = 'bg-blue-100 dark:bg-blue-900/30',
        ?string $url = null,
        ?Model $notifiable = null
    ): void {
        $ids = is_array($userIds) ? $userIds : [$userIds];

        foreach ($ids as $uid) {
            static::create([
                'user_id'         => $uid,
                'type'            => $type,
                'title'           => $title,
                'message'         => $message,
                'icon'            => $icon,
                'color'           => $color,
                'url'             => $url,
                'notifiable_type' => $notifiable ? get_class($notifiable) : null,
                'notifiable_id'   => $notifiable?->id,
            ]);
        }
    }
}
