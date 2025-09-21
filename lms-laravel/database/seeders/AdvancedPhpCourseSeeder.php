<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdvancedPhpCourseSeeder extends Seeder
{
    public function run(): void
    {
        $instructor = User::first();
        
        if (!$instructor) {
            $instructor = User::factory()->create([
                'name' => 'Dr. Sarah Johnson',
                'email' => 'instructor@example.com',
            ]);
        }

        $course = Course::where('title', 'Advanced PHP Development')->first();
        
        if (!$course) {
            $course = Course::create([
                'user_id' => $instructor->id,
                'title' => 'Advanced PHP Development',
                'description' => 'Dive deep into advanced PHP concepts including OOP principles, design patterns, modern PHP features, and best practices.',
                'published' => true,
            ]);
        }

        // Delete related records in proper order to avoid foreign key constraints
        $course->quizzes()->each(function ($quiz) {
            $quiz->questions()->each(function ($question) {
                $question->options()->delete();
            });
            $quiz->questions()->delete();
        });
        $course->quizzes()->delete();
        $course->lessons()->delete();

        $this->createLessons($course);
    }

    private function createLessons($course)
    {
        $lessons = [
            [
                'title' => 'Advanced Object-Oriented Programming',
                'description' => 'Master advanced OOP concepts including inheritance, polymorphism, abstraction, and encapsulation.',
                'content' => $this->getLesson1Content(),
                'duration' => '180 minutes',
                'youtube_video_id' => 'uUlLAfN3rJc', // Gary Clarke - OOP
                'order' => 1,
            ],
            [
                'title' => 'Design Patterns in PHP',
                'description' => 'Learn essential design patterns including Singleton, Factory, Observer, and Strategy patterns.',
                'content' => $this->getLesson2Content(),
                'duration' => '12 minutes',
                'youtube_video_id' => '_WDztTPrTDM', // Gary Clarke - Design Patterns intro
                'order' => 2,
            ],
            [
                'title' => 'Modern PHP Features (PHP 8+)',
                'description' => 'Explore new features in PHP 8+ including attributes, match expressions, and union types.',
                'content' => $this->getLesson3Content(),
                'duration' => '50 minutes',
                'youtube_video_id' => 'hotYRUOg8mg', // Derick Rethans - PHP 8.3
                'order' => 3,
            ],
            [
                'title' => 'Error Handling and Debugging',
                'description' => 'Learn advanced error handling techniques and debugging strategies for PHP applications.',
                'content' => $this->getLesson4Content(),
                'duration' => '10 minutes',
                'youtube_video_id' => 'UvElBs37JLg', // Xdebug 3 remote debugging
                'order' => 4,
            ],
            [
                'title' => 'Performance Optimization',
                'description' => 'Optimize PHP applications for better performance using profiling, caching, and best practices.',
                'content' => $this->getLesson5Content(),
                'duration' => '7 minutes',
                'youtube_video_id' => 'qvj6TFdgpCU', // Tideways - OPcache settings
                'order' => 5,
            ],
            [
                'title' => 'Security Best Practices',
                'description' => 'Implement security measures including input validation, SQL injection prevention, and authentication.',
                'content' => $this->getLesson6Content(),
                'duration' => '50 minutes',
                'youtube_video_id' => 'wC3fCf-t7eg', // OWASP Top Ten for PHP
                'order' => 6,
            ],
            [
                'title' => 'Testing with PHPUnit',
                'description' => 'Master unit testing, integration testing, and test-driven development with PHPUnit.',
                'content' => $this->getLesson7Content(),
                'duration' => '60 minutes',
                'youtube_video_id' => 'Pup4my_rQjQ', // Gary Clarke - PHPUnit
                'order' => 7,
            ],
            [
                'title' => 'Advanced Database Operations',
                'description' => 'Learn advanced database concepts including transactions, prepared statements, and ORM usage.',
                'content' => $this->getLesson8Content(),
                'duration' => '45 minutes',
                'youtube_video_id' => 'kEW6f7Pilc4', // Traversy Media - PDO Crash Course
                // alternative (transactions): 'e6yLUvpcOZo'
                'order' => 8,
            ],
        ];

        foreach ($lessons as $lessonData) {
            $lesson = Lesson::create(array_merge($lessonData, ['course_id' => $course->id]));
            $this->createQuizForLesson($course, $lesson, $lessonData['title'] . ' Quiz');
        }
    }

    private function createQuizForLesson($course, $lesson, $quizTitle)
    {
        $quiz = Quiz::create([
            'course_id' => $course->id,
            'title' => $quizTitle,
            'pass_score' => 70,
        ]);

        $this->createQuestionsForQuiz($quiz, $lesson->id);
    }

    private function createQuestionsForQuiz($quiz, $lessonId)
    {
        $questions = $this->getQuestionsForLesson($lessonId);
        
        foreach ($questions as $questionData) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'prompt' => $questionData['prompt'],
            ]);

            foreach ($questionData['options'] as $optionData) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }
    }

    private function getQuestionsForLesson($lessonId)
    {
        $questionSets = [
            1 => [
                [
                    'prompt' => 'What is the main principle of Object-Oriented Programming?',
                    'options' => [
                        ['text' => 'Code reusability', 'is_correct' => false],
                        ['text' => 'Encapsulation', 'is_correct' => true],
                        ['text' => 'Variable declaration', 'is_correct' => false],
                        ['text' => 'Function calls', 'is_correct' => false],
                    ]
                ],
                [
                    'prompt' => 'Which keyword is used to create a class in PHP?',
                    'options' => [
                        ['text' => 'class', 'is_correct' => true],
                        ['text' => 'object', 'is_correct' => false],
                        ['text' => 'function', 'is_correct' => false],
                        ['text' => 'var', 'is_correct' => false],
                    ]
                ]
            ],
            2 => [
                [
                    'prompt' => 'Which design pattern ensures only one instance of a class exists?',
                    'options' => [
                        ['text' => 'Factory Pattern', 'is_correct' => false],
                        ['text' => 'Singleton Pattern', 'is_correct' => true],
                        ['text' => 'Observer Pattern', 'is_correct' => false],
                        ['text' => 'Strategy Pattern', 'is_correct' => false],
                    ]
                ]
            ],
            3 => [
                [
                    'prompt' => 'Which PHP 8 feature allows passing arguments by name?',
                    'options' => [
                        ['text' => 'Named Arguments', 'is_correct' => true],
                        ['text' => 'Union Types', 'is_correct' => false],
                        ['text' => 'Match Expression', 'is_correct' => false],
                        ['text' => 'Attributes', 'is_correct' => false],
                    ]
                ],
                [
                    'prompt' => 'What is a match expression in PHP 8?',
                    'options' => [
                        ['text' => 'A more powerful alternative to switch statements', 'is_correct' => true],
                        ['text' => 'A way to create objects', 'is_correct' => false],
                        ['text' => 'A type declaration feature', 'is_correct' => false],
                        ['text' => 'A caching mechanism', 'is_correct' => false],
                    ]
                ],
                [
                    'prompt' => 'Which PHP 8.1 feature provides a way to define named constants?',
                    'options' => [
                        ['text' => 'Union Types', 'is_correct' => false],
                        ['text' => 'Enums', 'is_correct' => true],
                        ['text' => 'Named Arguments', 'is_correct' => false],
                        ['text' => 'Match Expression', 'is_correct' => false],
                    ]
                ]
            ]
        ];

        return $questionSets[$lessonId] ?? [
            [
                'prompt' => 'What is the main topic of this lesson?',
                'options' => [
                    ['text' => 'PHP Development', 'is_correct' => true],
                    ['text' => 'JavaScript', 'is_correct' => false],
                    ['text' => 'Python', 'is_correct' => false],
                    ['text' => 'Java', 'is_correct' => false],
                ]
            ]
        ];
    }

    private function getLesson1Content()
    {
        return '<h2>Advanced Object-Oriented Programming in PHP</h2>
        
        <h3>Introduction</h3>
        <p>Object-Oriented Programming (OOP) is a programming paradigm that uses objects and classes to organize code. PHP has evolved to support advanced OOP features that make code more maintainable, reusable, and scalable.</p>

        <h3>Key Concepts</h3>
        
        <h4>1. Classes and Objects</h4>
        <p>A class is a blueprint for creating objects. It defines properties and methods that the objects will have.</p>
        
        <pre><code class="language-php">class User {
    private string $name;
    private string $email;
    
    public function __construct(string $name, string $email) {
        $this->name = $name;
        $this->email = $email;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
}</code></pre>

        <h4>2. Inheritance</h4>
        <p>Inheritance allows a class to inherit properties and methods from another class.</p>
        
        <pre><code class="language-php">class Admin extends User {
    private array $permissions;
    
    public function __construct(string $name, string $email, array $permissions) {
        parent::__construct($name, $email);
        $this->permissions = $permissions;
    }
    
    public function hasPermission(string $permission): bool {
        return in_array($permission, $this->permissions);
    }
}</code></pre>

        <h4>3. Polymorphism</h4>
        <p>Polymorphism allows objects of different classes to be treated as objects of a common base class.</p>

        <h4>4. Abstraction</h4>
        <p>Abstraction hides complex implementation details and shows only essential features.</p>

        <h3>Best Practices</h3>
        <ul>
            <li>Use meaningful class and method names</li>
            <li>Follow the Single Responsibility Principle</li>
            <li>Use type declarations for better code clarity</li>
            <li>Implement proper encapsulation</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create a class hierarchy for a library management system with Book, Magazine, and DigitalBook classes that inherit from a common Item class.</p>';
    }

    private function getLesson2Content()
    {
        return '<h2>Design Patterns in PHP</h2>
        
        <h3>What are Design Patterns?</h3>
        <p>Design patterns are reusable solutions to common problems that occur in software design. They provide templates for how to solve design issues in a particular way.</p>

        <h3>Creational Patterns</h3>
        
        <h4>Singleton Pattern</h4>
        <p>Ensures a class has only one instance and provides global access to it.</p>
        
        <pre><code class="language-php">class DatabaseConnection {
    private static ?self $instance = null;
    private string $connection;
    
    private function __construct() {
        $this->connection = "Connected to database";
    }
    
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): string {
        return $this->connection;
    }
}</code></pre>

        <h4>Factory Pattern</h4>
        <p>Creates objects without specifying their exact class.</p>
        
        <pre><code class="language-php">interface PaymentMethod {
    public function processPayment(float $amount): bool;
}

class CreditCard implements PaymentMethod {
    public function processPayment(float $amount): bool {
        return true;
    }
}

class PaymentFactory {
    public static function create(string $type): PaymentMethod {
        return match($type) {
            \'credit_card\' => new CreditCard(),
            \'paypal\' => new PayPal(),
            default => throw new InvalidArgumentException("Unknown payment type")
        };
    }
}</code></pre>

        <h3>Behavioral Patterns</h3>
        
        <h4>Observer Pattern</h4>
        <p>Defines a one-to-many dependency between objects so that when one object changes state, all its dependents are notified.</p>

        <h4>Strategy Pattern</h4>
        <p>Defines a family of algorithms, encapsulates each one, and makes them interchangeable.</p>

        <h3>When to Use Design Patterns</h3>
        <ul>
            <li>When you have a recurring design problem</li>
            <li>When you need to improve code maintainability</li>
            <li>When you want to follow established best practices</li>
            <li>When working in a team environment</li>
        </ul>

        <h3>Exercise</h3>
        <p>Implement a Logger class using the Singleton pattern and a Notification system using the Observer pattern.</p>';
    }

    private function getLesson3Content()
    {
        return '<h2>Modern PHP Features (PHP 8+)</h2>
        
        <h3>PHP 8.0 Features</h3>
        
        <h4>Named Arguments</h4>
        <p>Allow passing arguments to functions by name instead of position.</p>
        
        <pre><code class="language-php">function createUser(string $name, string $email, int $age = 18, bool $active = true): User {
    return new User($name, $email, $age, $active);
}

// Using named arguments
$user = createUser(
    name: \'John Doe\',
    email: \'john@example.com\',
    active: false
);</code></pre>

        <h4>Match Expression</h4>
        <p>A more powerful and concise alternative to switch statements.</p>
        
        <pre><code class="language-php">$status = match($httpCode) {
    200 => \'OK\',
    404 => \'Not Found\',
    500 => \'Server Error\',
    default => \'Unknown Status\'
};</code></pre>

        <h4>Union Types</h4>
        <p>Allow a parameter or return type to accept multiple types.</p>
        
        <pre><code class="language-php">function processId(int|string $id): void {
    if (is_int($id)) {
        echo "Processing integer ID: $id";
    } else {
        echo "Processing string ID: $id";
    }
}</code></pre>

        <h3>PHP 8.1 Features</h3>
        
        <h4>Enums</h4>
        <p>Provide a way to define a set of named constants.</p>
        
        <pre><code class="language-php">enum Status: string {
    case PENDING = \'pending\';
    case APPROVED = \'approved\';
    case REJECTED = \'rejected\';
    
    public function getColor(): string {
        return match($this) {
            self::PENDING => \'yellow\',
            self::APPROVED => \'green\',
            self::REJECTED => \'red\'
        };
    }
}</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Use type declarations for better code clarity</li>
            <li>Leverage match expressions for cleaner conditional logic</li>
            <li>Use enums for representing fixed sets of values</li>
            <li>Take advantage of readonly properties for immutable objects</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create an enum for user roles and implement a user class with readonly properties using modern PHP syntax.</p>';
    }

    private function getLesson4Content()
    {
        return '<h2>Error Handling and Debugging</h2>
        
        <h3>Error Handling in PHP</h3>
        <p>Proper error handling is crucial for building robust applications. PHP provides several mechanisms for handling errors and exceptions.</p>

        <h3>Exception Handling</h3>
        
        <h4>Try-Catch Blocks</h4>
        <pre><code class="language-php">try {
    $result = riskyOperation();
    echo "Operation successful: $result";
} catch (InvalidArgumentException $e) {
    echo "Invalid argument: " . $e->getMessage();
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
} finally {
    echo "This always executes";
}</code></pre>

        <h4>Custom Exceptions</h4>
        <pre><code class="language-php">class ValidationException extends Exception {
    private array $errors;
    
    public function __construct(string $message, array $errors = []) {
        parent::__construct($message);
        $this->errors = $errors;
    }
    
    public function getErrors(): array {
        return $this->errors;
    }
}</code></pre>

        <h3>Debugging Techniques</h3>
        
        <h4>Using var_dump() and print_r()</h4>
        <pre><code class="language-php">$data = [\'name\' => \'John\', \'age\' => 30];
var_dump($data);  // Shows type and value
print_r($data);   // Human-readable format</code></pre>

        <h4>Logging</h4>
        <pre><code class="language-php">// Using error_log()
error_log("User login attempt: " . $username);

// Using Monolog (recommended)
use Monolog\\Logger;
use Monolog\\Handler\\StreamHandler;

$log = new Logger(\'app\');
$log->pushHandler(new StreamHandler(\'app.log\', Logger::WARNING));

$log->warning(\'Something went wrong\', [\'user_id\' => 123]);</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Always use try-catch blocks for operations that might fail</li>
            <li>Create custom exceptions for specific error types</li>
            <li>Log errors in production, display them in development</li>
            <li>Use proper error levels</li>
            <li>Implement graceful degradation</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create a file upload class with proper error handling and custom exceptions for different types of upload errors.</p>';
    }

    private function getLesson5Content()
    {
        return '<h2>Performance Optimization</h2>
        
        <h3>Why Performance Matters</h3>
        <p>Performance optimization is crucial for user experience, server costs, and scalability. Even small improvements can have significant impacts.</p>

        <h3>Code Optimization</h3>
        
        <h4>Efficient Loops</h4>
        <pre><code class="language-php">// Inefficient - calling count() in every iteration
for ($i = 0; $i < count($array); $i++) {
    // Process $array[$i]
}

// Efficient - count once
$count = count($array);
for ($i = 0; $i < $count; $i++) {
    // Process $array[$i]
}

// Even better - use foreach when possible
foreach ($array as $item) {
    // Process $item
}</code></pre>

        <h3>Caching Strategies</h3>
        
        <h4>OPcache</h4>
        <p>OPcache stores precompiled script bytecode in memory, eliminating the need for PHP to load and parse scripts on each request.</p>

        <h4>Application-Level Caching</h4>
        <pre><code class="language-php">// Simple file-based cache
function getCachedData(string $key, callable $callback) {
    $cacheFile = "cache/{$key}.cache";
    
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < 3600) {
        return unserialize(file_get_contents($cacheFile));
    }
    
    $data = $callback();
    file_put_contents($cacheFile, serialize($data));
    return $data;
}</code></pre>

        <h3>Database Optimization</h3>
        
        <h4>Query Optimization</h4>
        <pre><code class="language-php">// Inefficient - N+1 queries
$users = User::all();
foreach ($users as $user) {
    echo $user->profile->bio; // Executes a query for each user
}

// Efficient - eager loading
$users = User::with(\'profile\')->get();
foreach ($users as $user) {
    echo $user->profile->bio; // No additional queries
}</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Profile before optimizing</li>
            <li>Cache frequently accessed data</li>
            <li>Optimize database queries</li>
            <li>Use appropriate data structures</li>
            <li>Minimize external API calls</li>
        </ul>

        <h3>Exercise</h3>
        <p>Optimize a data processing script that reads a large CSV file and performs calculations on the data.</p>';
    }

    private function getLesson6Content()
    {
        return '<h2>Security Best Practices</h2>
        
        <h3>Security Fundamentals</h3>
        <p>Security should be a primary concern in every PHP application. This lesson covers essential security practices to protect your applications and users.</p>

        <h3>Input Validation and Sanitization</h3>
        
        <h4>Filter Input</h4>
        <pre><code class="language-php">// Validate email
$email = filter_input(INPUT_POST, \'email\', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    throw new InvalidArgumentException(\'Invalid email address\');
}

// Custom validation
function validatePassword(string $password): bool {
    return strlen($password) >= 8 && 
           preg_match(\'/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)/\', $password);
}</code></pre>

        <h3>SQL Injection Prevention</h3>
        
        <h4>Prepared Statements</h4>
        <pre><code class="language-php">// Vulnerable to SQL injection
$query = "SELECT * FROM users WHERE id = " . $_GET[\'id\'];

// Secure with prepared statements
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_GET[\'id\']]);
$user = $stmt->fetch();</code></pre>

        <h3>Password Security</h3>
        
        <h4>Password Hashing</h4>
        <pre><code class="language-php">// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Verify password
if (password_verify($password, $hashedPassword)) {
    // Password is correct
}</code></pre>

        <h3>CSRF Protection</h3>
        
        <h4>CSRF Token Implementation</h4>
        <pre><code class="language-php">// Generate CSRF token
function generateCsrfToken(): string {
    if (empty($_SESSION[\'csrf_token\'])) {
        $_SESSION[\'csrf_token\'] = bin2hex(random_bytes(32));
    }
    return $_SESSION[\'csrf_token\'];
}

// Verify CSRF token
function verifyCsrfToken(string $token): bool {
    return isset($_SESSION[\'csrf_token\']) && 
           hash_equals($_SESSION[\'csrf_token\'], $token);
}</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Never trust user input</li>
            <li>Use HTTPS in production</li>
            <li>Keep PHP and dependencies updated</li>
            <li>Implement proper error handling</li>
            <li>Use strong, unique passwords</li>
            <li>Regular security audits</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create a secure user registration form with proper validation, CSRF protection, and password hashing.</p>';
    }

    private function getLesson7Content()
    {
        return '<h2>Testing with PHPUnit</h2>
        
        <h3>Introduction to Testing</h3>
        <p>Testing is a crucial part of software development that helps ensure code quality, catch bugs early, and make refactoring safer.</p>

        <h3>PHPUnit Basics</h3>
        
        <h4>Basic Test Structure</h4>
        <pre><code class="language-php">use PHPUnit\\Framework\\TestCase;

class CalculatorTest extends TestCase {
    public function testAdd(): void {
        $calculator = new Calculator();
        $result = $calculator->add(2, 3);
        $this->assertEquals(5, $result);
    }
}</code></pre>

        <h3>Assertions</h3>
        
        <h4>Common Assertions</h4>
        <pre><code class="language-php">// Equality assertions
$this->assertEquals($expected, $actual);
$this->assertSame($expected, $actual); // Strict equality

// Type assertions
$this->assertIsArray($variable);
$this->assertIsString($variable);

// Boolean assertions
$this->assertTrue($condition);
$this->assertFalse($condition);

// Exception assertions
$this->expectException(InvalidArgumentException::class);
$this->expectExceptionMessage(\'Invalid input\');</code></pre>

        <h3>Mocking</h3>
        
        <h4>Creating Mocks</h4>
        <pre><code class="language-php">class UserServiceTest extends TestCase {
    public function testCreateUser(): void {
        // Create a mock for the email service
        $emailService = $this->createMock(EmailService::class);
        $emailService->expects($this->once())
                    ->method(\'sendWelcomeEmail\')
                    ->with($this->isInstanceOf(User::class));
        
        $userService = new UserService($emailService);
        $user = $userService->createUser(\'john@example.com\', \'password\');
        
        $this->assertInstanceOf(User::class, $user);
    }
}</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Write tests before or alongside your code</li>
            <li>Test behavior, not implementation</li>
            <li>Keep tests simple and focused</li>
            <li>Use descriptive test names</li>
            <li>Aim for high test coverage</li>
            <li>Mock external dependencies</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create a comprehensive test suite for a User class that includes unit tests, integration tests, and proper mocking of dependencies.</p>';
    }

    private function getLesson8Content()
    {
        return '<h2>Advanced Database Operations</h2>
        
        <h3>Database Design Principles</h3>
        <p>Understanding advanced database concepts is essential for building scalable and efficient applications.</p>

        <h3>Transactions</h3>
        
        <h4>ACID Properties</h4>
        <ul>
            <li><strong>Atomicity:</strong> All operations succeed or all fail</li>
            <li><strong>Consistency:</strong> Database remains in valid state</li>
            <li><strong>Isolation:</strong> Concurrent transactions don\'t interfere</li>
            <li><strong>Durability:</strong> Committed changes persist</li>
        </ul>

        <h4>Using Transactions</h4>
        <pre><code class="language-php">try {
    $pdo->beginTransaction();
    
    // Transfer money between accounts
    $stmt1 = $pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
    $stmt1->execute([$amount, $fromAccountId]);
    
    $stmt2 = $pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
    $stmt2->execute([$amount, $toAccountId]);
    
    $pdo->commit();
    echo "Transfer successful";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Transfer failed: " . $e->getMessage();
}</code></pre>

        <h3>Prepared Statements</h3>
        
        <h4>Benefits of Prepared Statements</h4>
        <ul>
            <li>Prevent SQL injection</li>
            <li>Better performance for repeated queries</li>
            <li>Type safety</li>
        </ul>

        <pre><code class="language-php">// Named placeholders
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND status = :status");
$stmt->execute([
    \':email\' => $email,
    \':status\' => \'active\'
]);</code></pre>

        <h3>Query Optimization</h3>
        
        <h4>Indexing</h4>
        <pre><code class="sql">-- Create index on frequently queried columns
CREATE INDEX idx_user_email ON users(email);
CREATE INDEX idx_user_status ON users(status);

-- Composite index for multiple columns
CREATE INDEX idx_user_status_created ON users(status, created_at);</code></pre>

        <h3>Best Practices</h3>
        <ul>
            <li>Use transactions for related operations</li>
            <li>Always use prepared statements</li>
            <li>Create appropriate indexes</li>
            <li>Monitor query performance</li>
            <li>Use connection pooling for high-traffic applications</li>
            <li>Regular database maintenance</li>
        </ul>

        <h3>Exercise</h3>
        <p>Create a database schema for a blog system with proper relationships, indexes, and implement CRUD operations with transactions.</p>';
    }
}
