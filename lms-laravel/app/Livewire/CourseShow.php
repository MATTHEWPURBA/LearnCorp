<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use Livewire\Component;

class CourseShow extends Component
{
    public Course $course;
    public $enrollment;
    public $currentLessonId;

    public function mount(Course $course)
    {
        $this->course = $course->load(['lessons', 'instructor', 'liveSessions']);
        $this->enrollment = auth()->user()->getEnrollmentFor($course);
        
        if ($this->enrollment && $this->course->lessons->isNotEmpty()) {
            $this->currentLessonId = $this->course->lessons->first()->id;
        }
    }

    public function selectLesson($lessonId)
    {
        $this->currentLessonId = $lessonId;
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
            session()->flash('message', 'Successfully enrolled in ' . $this->course->title . '!');
        }
    }

    public function getCurrentLessonProperty()
    {
        return $this->course->lessons->find($this->currentLessonId);
    }

    public function render()
    {
        return view('livewire.course-show');
    }
}
