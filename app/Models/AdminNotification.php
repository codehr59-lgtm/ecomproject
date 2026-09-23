<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminNotification extends Model
{
    protected $fillable = [
        'title', 'body', 'type', 'icon', 'action_url',
        'user_id', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, ?int $userId = null)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('user_id');
            if ($userId) {
                $q->orWhere('user_id', $userId);
            }
        });
    }

    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public static function notify(string $title, ?string $body = null, string $type = 'info', ?string $actionUrl = null, ?int $userId = null): self
    {
        return static::create([
            'title'      => $title,
            'body'       => $body,
            'type'       => $type,
            'action_url' => $actionUrl,
            'user_id'    => $userId,
        ]);
    }
}
