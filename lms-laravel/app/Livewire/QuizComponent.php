<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\Attempt;
use App\Models\Badge;
use App\Models\UserPoints;
use Livewire\Component;

class QuizComponent extends Component
{
    public Quiz $quiz;
    public $answers = [];
    public $submitted = false;
    public $attempt = null;
    public $startTime;
    public $timeSpent = 0;
    public $pointsEarned = 0;
    public $badgesEarned = [];
    public $achievements = [];

    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load(['questions.options', 'course']);
        $this->startTime = now();
    }

    public function submitQuiz()
    {
        $this->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|exists:options,id',
        ]);

        $this->timeSpent = now()->diffInSeconds($this->startTime);
        $score = $this->quiz->calculateScore($this->answers);
        
        $this->attempt = Attempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'answers' => $this->answers,
        ]);

        $this->submitted = true;
        $this->processGamification($score);
        
        if ($this->quiz->isPassingScore($score)) {
            session()->flash('success', "Congratulations! You passed with a score of {$score}%");
        } else {
            session()->flash('error', "You scored {$score}%. You need {$this->quiz->pass_score}% to pass. Try again!");
        }
    }

    private function processGamification($score)
    {
        $user = auth()->user();
        $points = 0;
        $badges = [];
        $achievements = [];

        // Base points for completing quiz
        $points += 50;
        $user->addPoints(50, 'quiz_completion', $this->quiz->course, $this->quiz, 'Completed quiz: ' . $this->quiz->title);

        // Bonus points for score
        if ($score >= 90) {
            $points += 50;
            $user->addPoints(50, 'quiz_excellent', $this->quiz->course, $this->quiz, 'Excellent score on quiz');
        } elseif ($score >= 80) {
            $points += 25;
            $user->addPoints(25, 'quiz_good', $this->quiz->course, $this->quiz, 'Good score on quiz');
        }

        // Perfect score bonus
        if ($score == 100) {
            $points += 100;
            $user->addPoints(100, 'quiz_perfect', $this->quiz->course, $this->quiz, 'Perfect score on quiz');
            
            // Check for perfect score badge
            $perfectBadge = Badge::where('type', 'quiz')
                ->whereJsonContains('criteria->quiz_perfect', 1)
                ->first();
            
            if ($perfectBadge && !$user->hasBadge($perfectBadge)) {
                $user->earnBadge($perfectBadge, $this->quiz->course);
                $badges[] = $perfectBadge;
            }
        }

        // Speed bonus
        if ($this->timeSpent < 120) { // Under 2 minutes
            $points += 25;
            $user->addPoints(25, 'quiz_speed', $this->quiz->course, $this->quiz, 'Fast completion bonus');
            
            // Check for speed badge
            $speedBadge = Badge::where('type', 'quiz')
                ->whereJsonContains('criteria->quiz_speed', 120)
                ->first();
            
            if ($speedBadge && !$user->hasBadge($speedBadge)) {
                $user->earnBadge($speedBadge, $this->quiz->course);
                $badges[] = $speedBadge;
            }
        }

        // First quiz badge
        $firstQuizBadge = Badge::where('type', 'quiz')
            ->whereJsonContains('criteria->quiz_completed', 1)
            ->first();
        
        if ($firstQuizBadge && !$user->hasBadge($firstQuizBadge)) {
            $user->earnBadge($firstQuizBadge, $this->quiz->course);
            $badges[] = $firstQuizBadge;
        }

        // Quiz master badge (10 quizzes)
        $quizCount = $user->attempts()->count();
        $quizMasterBadge = Badge::where('type', 'quiz')
            ->whereJsonContains('criteria->quiz_completed', 10)
            ->first();
        
        if ($quizMasterBadge && !$user->hasBadge($quizMasterBadge) && $quizCount >= 10) {
            $user->earnBadge($quizMasterBadge, $this->quiz->course);
            $badges[] = $quizMasterBadge;
        }

        // Add achievements
        if ($score == 100) {
            $achievements[] = $user->addAchievement(
                'quiz_perfect',
                'Perfect Score!',
                "You got a perfect score on {$this->quiz->title}",
                100,
                ['quiz_id' => $this->quiz->id, 'score' => $score]
            );
        }

        if ($this->timeSpent < 60) {
            $achievements[] = $user->addAchievement(
                'quiz_speed',
                'Lightning Fast!',
                "You completed {$this->quiz->title} in under 1 minute",
                50,
                ['quiz_id' => $this->quiz->id, 'time_spent' => $this->timeSpent]
            );
        }

        $this->pointsEarned = $points;
        $this->badgesEarned = $badges;
        $this->achievements = $achievements;
    }

    public function retakeQuiz()
    {
        $this->answers = [];
        $this->submitted = false;
        $this->attempt = null;
        $this->startTime = now();
        $this->timeSpent = 0;
        $this->pointsEarned = 0;
        $this->badgesEarned = [];
        $this->achievements = [];
    }

    public function render()
    {
        return view('livewire.quiz-component');
    }
}
