<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Quiz badges
            [
                'name' => 'First Quiz',
                'description' => 'Complete your first quiz',
                'icon' => '🎯',
                'color' => '#10B981',
                'type' => 'quiz',
                'criteria' => ['quiz_completed' => 1],
                'points_reward' => 50,
            ],
            [
                'name' => 'Quiz Master',
                'description' => 'Complete 10 quizzes',
                'icon' => '🏆',
                'color' => '#F59E0B',
                'type' => 'quiz',
                'criteria' => ['quiz_completed' => 10],
                'points_reward' => 200,
            ],
            [
                'name' => 'Perfect Score',
                'description' => 'Get 100% on a quiz',
                'icon' => '💯',
                'color' => '#8B5CF6',
                'type' => 'quiz',
                'criteria' => ['quiz_perfect' => 1],
                'points_reward' => 100,
            ],
            [
                'name' => 'Speed Demon',
                'description' => 'Complete a quiz in under 2 minutes',
                'icon' => '⚡',
                'color' => '#EF4444',
                'type' => 'quiz',
                'criteria' => ['quiz_speed' => 120], // 2 minutes in seconds
                'points_reward' => 75,
            ],

            // Course badges
            [
                'name' => 'Course Starter',
                'description' => 'Enroll in your first course',
                'icon' => '📚',
                'color' => '#3B82F6',
                'type' => 'course',
                'criteria' => ['course_enrolled' => 1],
                'points_reward' => 25,
            ],
            [
                'name' => 'Course Completer',
                'description' => 'Complete your first course',
                'icon' => '🎓',
                'color' => '#10B981',
                'type' => 'course',
                'criteria' => ['course_completed' => 1],
                'points_reward' => 500,
            ],
            [
                'name' => 'Advanced PHP Expert',
                'description' => 'Complete the Advanced PHP Development course',
                'icon' => '🐘',
                'color' => '#7C3AED',
                'type' => 'course',
                'criteria' => ['course_id' => 1, 'course_completed' => 1],
                'points_reward' => 1000,
            ],

            // Streak badges
            [
                'name' => 'Daily Learner',
                'description' => 'Study for 3 consecutive days',
                'icon' => '🔥',
                'color' => '#F97316',
                'type' => 'streak',
                'criteria' => ['streak_days' => 3],
                'points_reward' => 100,
            ],
            [
                'name' => 'Week Warrior',
                'description' => 'Study for 7 consecutive days',
                'icon' => '💪',
                'color' => '#DC2626',
                'type' => 'streak',
                'criteria' => ['streak_days' => 7],
                'points_reward' => 300,
            ],
            [
                'name' => 'Month Master',
                'description' => 'Study for 30 consecutive days',
                'icon' => '👑',
                'color' => '#7C2D12',
                'type' => 'streak',
                'criteria' => ['streak_days' => 30],
                'points_reward' => 1000,
            ],

            // Achievement badges
            [
                'name' => 'Early Bird',
                'description' => 'Complete a lesson before 8 AM',
                'icon' => '🌅',
                'color' => '#F59E0B',
                'type' => 'achievement',
                'criteria' => ['early_completion' => 1],
                'points_reward' => 50,
            ],
            [
                'name' => 'Night Owl',
                'description' => 'Complete a lesson after 10 PM',
                'icon' => '🦉',
                'color' => '#1F2937',
                'type' => 'achievement',
                'criteria' => ['late_completion' => 1],
                'points_reward' => 50,
            ],
            [
                'name' => 'Social Learner',
                'description' => 'Participate in 5 live sessions',
                'icon' => '👥',
                'color' => '#EC4899',
                'type' => 'achievement',
                'criteria' => ['live_sessions' => 5],
                'points_reward' => 200,
            ],
        ];

        foreach ($badges as $badgeData) {
            Badge::create($badgeData);
        }
    }
}