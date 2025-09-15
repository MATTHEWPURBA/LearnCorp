<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\Attempt;
use Livewire\Component;

class QuizComponent extends Component
{
    public Quiz $quiz;
    public $answers = [];
    public $submitted = false;
    public $attempt = null;

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load(['questions.options']);
    }

    public function submitQuiz()
    {
        $this->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:options,id',
        ]);

        $score = $this->quiz->calculateScore($this->answers);
        
        $this->attempt = Attempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'answers' => $this->answers,
        ]);

        $this->submitted = true;
        
        if ($this->quiz->isPassingScore($score)) {
            session()->flash('success', "Congratulations! You passed with a score of {$score}%");
        } else {
            session()->flash('error', "You scored {$score}%. You need {$this->quiz->pass_score}% to pass. Try again!");
        }
    }

    public function retakeQuiz()
    {
        $this->answers = [];
        $this->submitted = false;
        $this->attempt = null;
    }

    public function render()
    {
        return view('livewire.quiz-component');
    }
}
