<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\Enrollment;
use Livewire\Component;

class LessonPlayer extends Component
{
    public Lesson $lesson;
    public $enrollment;
    public $showQuiz = false;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson->load(['course', 'quiz.questions.options']);
        $this->enrollment = auth()->user()->getEnrollmentFor($this->lesson->course);
    }

    public function markAsCompleted()
    {
        if ($this->enrollment) {
            $totalLessons = $this->lesson->course->lessons()->count();
            $completedLessons = $this->lesson->course->lessons()
                ->where('order', '<=', $this->lesson->order)
                ->count();
            
            $progress = round(($completedLessons / $totalLessons) * 100);
            $this->enrollment->updateProgress($progress);
            
            session()->flash('message', 'Lesson marked as completed!');
        }
    }

    public function toggleQuiz()
    {
        $this->showQuiz = !$this->showQuiz;
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
