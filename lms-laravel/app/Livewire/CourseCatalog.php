<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CourseCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 12;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        if (!auth()->user()->isEnrolledIn($course)) {
            auth()->user()->enrolledCourses()->attach($courseId, [
                'progress' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            session()->flash('message', 'Successfully enrolled in ' . $course->title . '!');
        } else {
            session()->flash('error', 'You are already enrolled in this course.');
        }
    }

    public function render()
    {
        $courses = Course::where('published', true)
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->with(['instructor', 'enrollments'])
            ->paginate($this->perPage);

        return view('livewire.course-catalog', compact('courses'));
    }
}
