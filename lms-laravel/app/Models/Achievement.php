<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'points_earned',
        'metadata',
        'achieved_at',
    ];

    protected $casts = [
        'points_earned' => 'integer',
        'metadata' => 'array',
        'achieved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('achieved_at', '>=', now()->subDays($days));
    }
}
