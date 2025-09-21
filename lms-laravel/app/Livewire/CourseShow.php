<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Badge;
use Livewire\Component;

class CourseShow extends Component
{
    public Course $course;
    public $enrollment;
    public $currentLessonId;
    public $showQuiz = false;
    public $selectedQuiz = null;

    public function mount(Course $course)
    {
        $this->course = $course->load(['lessons', 'instructor', 'liveSessions', 'quizzes.questions']);
        $this->enrollment = auth()->user()->getEnrollmentFor($course);
        
        if ($this->enrollment && $this->course->lessons->isNotEmpty()) {
            $this->currentLessonId = $this->course->lessons->first()->id;
        }
    }

    public function selectLesson($lessonId)
    {
        $this->currentLessonId = $lessonId;
        $this->showQuiz = false;
        $this->selectedQuiz = null;
    }

    public function showQuizForLesson($lessonId)
    {
        $lesson = $this->course->lessons->find($lessonId);
        $quiz = $this->course->quizzes->where('title', 'like', '%' . $lesson->title . '%')->first();
        
        // Alternative matching if the first one doesn't work
        if (!$quiz) {
            $quiz = $this->course->quizzes->where('title', $lesson->title . ' Quiz')->first();
        }
        
        if ($quiz) {
            $this->selectedQuiz = $quiz;
            $this->showQuiz = true;
        }
    }

    public function hideQuiz()
    {
        $this->showQuiz = false;
        $this->selectedQuiz = null;
    }

    public function enroll()
    {
        if (!$this->enrollment) {
            $this->enrollment = auth()->user()->enrolledCourses()->attach($this->course->id, [
                'progress' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $this->enrollment = auth()->user()->getEnrollmentFor($this->course);
            
            // Award enrollment badge
            $enrollmentBadge = Badge::where('type', 'course')
                ->whereJsonContains('criteria->course_enrolled', 1)
                ->first();
            
            if ($enrollmentBadge && !auth()->user()->hasBadge($enrollmentBadge)) {
                auth()->user()->earnBadge($enrollmentBadge, $this->course);
            }
            
            // Add enrollment points
            auth()->user()->addPoints(25, 'course_enrollment', $this->course, null, 'Enrolled in course');
            
            session()->flash('message', 'Successfully enrolled in ' . $this->course->title . '!');
        }
    }

    public function getCurrentLessonProperty()
    {
        return $this->course->lessons->find($this->currentLessonId);
    }

    public function getUserStatsProperty()
    {
        if (!$this->enrollment) {
            return null;
        }

        $user = auth()->user();
        return [
            'total_points' => $user->getTotalPoints(),
            'course_points' => $user->getCoursePoints($this->course),
            'badges_count' => $user->badges()->count(),
            'recent_achievements' => $user->achievements()->recent(7)->take(3)->get(),
        ];
    }

    public function render()
    {
        return view('livewire.course-show');
    }
}
