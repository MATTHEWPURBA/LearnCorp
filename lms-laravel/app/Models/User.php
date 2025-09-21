<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // LMS Relationships
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot(['completed_at', 'progress'])
                    ->withTimestamps();
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // Gamification relationships
    public function points(): HasMany
    {
        return $this->hasMany(UserPoints::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot(['course_id', 'earned_at'])
                    ->withTimestamps();
    }

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrolledCourses()->where('course_id', $course->id)->exists();
    }

    public function getEnrollmentFor(Course $course): ?Enrollment
    {
        return $this->enrollments()->where('course_id', $course->id)->first();
    }

    // Gamification methods
    public function getTotalPoints(): int
    {
        return $this->points()->sum('points');
    }

    public function getCoursePoints(Course $course): int
    {
        return $this->points()->where('course_id', $course->id)->sum('points');
    }

    public function addPoints(int $points, string $source, ?Course $course = null, ?Quiz $quiz = null, ?string $description = null): UserPoints
    {
        return $this->points()->create([
            'points' => $points,
            'source' => $source,
            'course_id' => $course?->id,
            'quiz_id' => $quiz?->id,
            'description' => $description,
        ]);
    }

    public function hasBadge(Badge $badge): bool
    {
        return $this->badges()->where('badge_id', $badge->id)->exists();
    }

    public function earnBadge(Badge $badge, ?Course $course = null): UserBadge
    {
        return $this->userBadges()->create([
            'badge_id' => $badge->id,
            'course_id' => $course?->id,
            'earned_at' => now(),
        ]);
    }

    public function addAchievement(string $type, string $title, string $description, int $points = 0, array $metadata = []): Achievement
    {
        return $this->achievements()->create([
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'points_earned' => $points,
            'metadata' => $metadata,
            'achieved_at' => now(),
        ]);
    }
}
