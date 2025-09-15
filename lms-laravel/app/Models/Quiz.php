<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'pass_score',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function calculateScore(array $answers): int
    {
        $correct = 0;
        $total = $this->questions()->count();
        
        foreach ($this->questions as $question) {
            $userAnswer = $answers[$question->id] ?? null;
            $correctOption = $question->options()->where('is_correct', true)->first();
            
            if ($correctOption && $userAnswer == $correctOption->id) {
                $correct++;
            }
        }
        
        return $total > 0 ? round(($correct / $total) * 100) : 0;
    }

    public function isPassingScore(int $score): bool
    {
        return $score >= $this->pass_score;
    }
}
