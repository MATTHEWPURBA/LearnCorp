<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Course;
use Livewire\Component;

class Leaderboard extends Component
{
    public $course = null;
    public $timeframe = 'all'; // 'week', 'month', 'all'
    public $type = 'points'; // 'points', 'badges', 'quizzes'

    public function mount(Course $course = null)
    {
        $this->course = $course;
    }

    public function setTimeframe($timeframe)
    {
        $this->timeframe = $timeframe;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getLeaderboardDataProperty()
    {
        $query = User::query();

        if ($this->course) {
            $query->whereHas('points', function ($q) {
                $q->where('course_id', $this->course->id);
            });
        }

        if ($this->timeframe !== 'all') {
            $days = $this->timeframe === 'week' ? 7 : 30;
            $query->whereHas('points', function ($q) use ($days) {
                $q->where('created_at', '>=', now()->subDays($days));
            });
        }

        return $query->withCount(['points', 'badges', 'achievements'])
            ->with(['points' => function ($query) {
                if ($this->course) {
                    $query->where('course_id', $this->course->id);
                }
                if ($this->timeframe !== 'all') {
                    $days = $this->timeframe === 'week' ? 7 : 30;
                    $query->where('created_at', '>=', now()->subDays($days));
                }
            }])
            ->get()
            ->map(function ($user) {
                $user->total_points = $user->points->sum('points');
                $user->course_points = $this->course ? $user->getCoursePoints($this->course) : $user->total_points;
                return $user;
            })
            ->sortByDesc(function ($user) {
                switch ($this->type) {
                    case 'points':
                        return $this->course ? $user->course_points : $user->total_points;
                    case 'badges':
                        return $user->badges_count;
                    case 'quizzes':
                        return $user->attempts()->count();
                    default:
                        return $user->total_points;
                }
            })
            ->take(10)
            ->values();
    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}