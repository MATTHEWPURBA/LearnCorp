<?php

namespace Database\Seeders;

use App\Models\Course;
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
        // Get the first user as instructor (or create one if none exists)
        $instructor = User::first();
        
        if (!$instructor) {
            $instructor = User::factory()->create([
                'name' => 'John Doe',
                'email' => 'instructor@example.com',
            ]);
        }

        $courses = [
            [
                'user_id' => $instructor->id,
                'title' => 'Laravel Fundamentals',
                'description' => 'Learn the basics of Laravel framework including routing, controllers, models, and views. Perfect for beginners who want to start building web applications with PHP.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'Advanced PHP Development',
                'description' => 'Dive deep into advanced PHP concepts including OOP principles, design patterns, and modern PHP features. This course will take your PHP skills to the next level.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'Database Design & Optimization',
                'description' => 'Master database design principles, normalization, indexing, and query optimization. Learn how to build efficient and scalable database systems.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'JavaScript ES6+ Mastery',
                'description' => 'Explore modern JavaScript features including arrow functions, destructuring, async/await, and modules. Build dynamic and interactive web applications.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'React.js Complete Guide',
                'description' => 'From basics to advanced concepts, learn React.js including hooks, context, state management, and building real-world applications.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'Node.js Backend Development',
                'description' => 'Build scalable server-side applications with Node.js. Learn Express.js, RESTful APIs, authentication, and deployment strategies.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'Docker & DevOps Basics',
                'description' => 'Introduction to containerization with Docker, CI/CD pipelines, and DevOps practices. Learn how to deploy and manage applications effectively.',
                'thumbnail' => null,
                'published' => true,
            ],
            [
                'user_id' => $instructor->id,
                'title' => 'AWS Cloud Computing',
                'description' => 'Master Amazon Web Services including EC2, S3, RDS, and more. Learn cloud architecture and deployment strategies for modern applications.',
                'thumbnail' => null,
                'published' => true,
            ]
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}
