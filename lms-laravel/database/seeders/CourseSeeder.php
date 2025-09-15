<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\LiveSession;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample instructor
        $instructor = User::firstOrCreate(
            ['email' => 'instructor@example.com'],
            [
                'name' => 'John Instructor',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Sample courses data
        $coursesData = [
            [
                'title' => 'Introduction to Web Development',
                'slug' => 'intro-web-dev',
                'description' => 'Learn the fundamentals of modern web development with HTML, CSS, and JavaScript. Perfect for beginners who want to start their coding journey.',
                'thumbnail' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=500&h=300&fit=crop',
                'published' => true,
                'lessons' => [
                    [
                        'title' => 'HTML Fundamentals',
                        'description' => 'Learn the building blocks of web pages with HTML',
                        'youtube_video_id' => 'pQN-pnXPaVg',
                        'order' => 1,
                        'quiz' => [
                            'title' => 'HTML Basics Quiz',
                            'questions' => [
                                [
                                    'prompt' => 'What does HTML stand for?',
                                    'options' => [
                                        ['text' => 'HyperText Markup Language', 'is_correct' => true],
                                        ['text' => 'High Tech Modern Language', 'is_correct' => false],
                                        ['text' => 'Home Tool Markup Language', 'is_correct' => false],
                                        ['text' => 'Hyperlink and Text Markup Language', 'is_correct' => false],
                                    ]
                                ],
                                [
                                    'prompt' => 'Which tag is used to create a paragraph?',
                                    'options' => [
                                        ['text' => '<para>', 'is_correct' => false],
                                        ['text' => '<p>', 'is_correct' => true],
                                        ['text' => '<paragraph>', 'is_correct' => false],
                                        ['text' => '<text>', 'is_correct' => false],
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'title' => 'CSS Styling',
                        'description' => 'Make your web pages beautiful with CSS',
                        'youtube_video_id' => '1Rs2ND1ryYc',
                        'order' => 2,
                    ],
                    [
                        'title' => 'JavaScript Basics',
                        'description' => 'Add interactivity to your web pages with JavaScript',
                        'youtube_video_id' => 'W6NZfCO5SIk',
                        'order' => 3,
                    ]
                ]
            ],
            [
                'title' => 'React Development Masterclass',
                'slug' => 'react-masterclass',
                'description' => 'Build dynamic user interfaces with React. Learn component-based architecture, hooks, state management, and modern development practices.',
                'thumbnail' => 'https://images.unsplash.com/photo-1633356122544-f134324a6cee?w=500&h=300&fit=crop',
                'published' => true,
                'lessons' => [
                    [
                        'title' => 'Components and JSX',
                        'description' => 'Learn the basics of React components and JSX syntax',
                        'order' => 1,
                    ],
                    [
                        'title' => 'State and Props',
                        'description' => 'Manage component state and pass data with props',
                        'order' => 2,
                    ],
                    [
                        'title' => 'Hooks Deep Dive',
                        'description' => 'Use React hooks for state management and side effects',
                        'order' => 3,
                    ]
                ]
            ],
            [
                'title' => 'Python for Data Science',
                'slug' => 'python-data-science',
                'description' => 'Master Python programming for data analysis, visualization, and machine learning. Learn pandas, numpy, matplotlib, and scikit-learn.',
                'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=500&h=300&fit=crop',
                'published' => true,
                'lessons' => [
                    [
                        'title' => 'Python Basics',
                        'description' => 'Introduction to Python programming fundamentals',
                        'order' => 1,
                    ],
                    [
                        'title' => 'NumPy Arrays',
                        'description' => 'Working with numerical data using NumPy',
                        'order' => 2,
                    ],
                    [
                        'title' => 'Pandas DataFrames',
                        'description' => 'Data manipulation and analysis with pandas',
                        'order' => 3,
                    ]
                ]
            ]
        ];

        foreach ($coursesData as $courseData) {
            $lessons = $courseData['lessons'];
            unset($courseData['lessons']);

            $course = Course::create([
                ...$courseData,
                'user_id' => $instructor->id,
            ]);

            foreach ($lessons as $lessonData) {
                $quizData = $lessonData['quiz'] ?? null;
                unset($lessonData['quiz']);

                $lesson = $course->lessons()->create($lessonData);

                if ($quizData) {
                    $quiz = $course->quizzes()->create([
                        'title' => $quizData['title'],
                        'pass_score' => 70,
                    ]);

                    foreach ($quizData['questions'] as $questionData) {
                        $options = $questionData['options'];
                        unset($questionData['options']);

                        $question = $quiz->questions()->create([
                            'prompt' => $questionData['prompt'],
                        ]);

                        foreach ($options as $optionData) {
                            $question->options()->create($optionData);
                        }
                    }
                }
            }

            // Create a live session for each course
            $course->liveSessions()->create([
                'title' => $course->title . ' - Live Q&A Session',
                'start_time' => now()->addDays(7),
                'end_time' => now()->addDays(7)->addHours(2),
                'status' => 'scheduled',
            ]);
        }
    }
}
