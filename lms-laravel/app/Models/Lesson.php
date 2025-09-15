<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'youtube_video_id',
        'asset_path',
        'order',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function getAssetUrlAttribute()
    {
        if ($this->asset_path) {
            return Storage::disk('r2')->url($this->asset_path);
        }
        return null;
    }

    public function getYoutubeEmbedUrlAttribute()
    {
        if ($this->youtube_video_id) {
            return "https://www.youtube.com/embed/{$this->youtube_video_id}?rel=0&modestbranding=1&enablejsapi=1&origin=" . urlencode(config('app.url'));
        }
        return null;
    }
}
