<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Course Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 mb-6 text-white">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-4xl font-bold mb-3">{{ $course->title }}</h1>
                    <p class="text-blue-100 text-lg mb-6">{{ $course->description }}</p>
                    <div class="flex items-center space-x-6 text-blue-100">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Instructor: {{ $course->instructor->name }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $course->lessons->count() }} lessons</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $course->quizzes->count() }} quizzes</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $course->liveSessions->count() }} live sessions</span>
                        </div>
                    </div>
                </div>
                <div class="ml-6">
                    @if($enrollment)
                        <div class="bg-green-500 text-white px-6 py-3 rounded-lg font-medium text-center">
                            <div class="text-sm">Status</div>
                            <div class="text-lg font-bold">Enrolled</div>
                        </div>
                    @else
                        <button wire:click="enroll" 
                                class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-medium transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Enroll Now
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Stats (if enrolled) -->
        @if($enrollment && $this->userStats)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Your Progress
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $this->userStats['total_points'] }}</div>
                        <div class="text-sm text-gray-600">Total Points</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $this->userStats['course_points'] }}</div>
                        <div class="text-sm text-gray-600">Course Points</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $this->userStats['badges_count'] }}</div>
                        <div class="text-sm text-gray-600">Badges Earned</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-orange-600">{{ $this->userStats['recent_achievements']->count() }}</div>
                        <div class="text-sm text-gray-600">Recent Achievements</div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lessons Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Course Content</h2>
                    
                    @if($course->lessons->count() > 0)
                        <div class="space-y-3">
                            @foreach($course->lessons as $lesson)
                                <div class="border border-gray-200 rounded-xl overflow-hidden {{ $currentLessonId == $lesson->id ? 'ring-2 ring-blue-500 shadow-md' : 'hover:shadow-md' }} transition-all">
                                    <button wire:click="selectLesson({{ $lesson->id }})"
                                            class="w-full text-left p-4 {{ $currentLessonId == $lesson->id ? 'bg-blue-50 text-blue-900' : 'hover:bg-gray-50' }} transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h3 class="font-semibold text-lg">{{ $lesson->title }}</h3>
                                                    @if($lesson->youtube_video_id)
                                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2">{{ $lesson->description ?? 'No description' }}</p>
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $lesson->duration ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                    
                                    <!-- Quiz Button -->
                                    @if($enrollment)
                                        @php
                                            $quiz = $course->quizzes->where('title', 'like', '%' . $lesson->title . '%')->first();
                                            // Alternative matching if the first one doesn't work
                                            if (!$quiz) {
                                                $quiz = $course->quizzes->where('title', $lesson->title . ' Quiz')->first();
                                            }
                                        @endphp
                                        @if($quiz)
                                            <div class="border-t border-gray-200 bg-gray-50 px-4 py-3">
                                                <button wire:click="showQuizForLesson({{ $lesson->id }})"
                                                        class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-all duration-200 text-sm flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Take Quiz
                                                </button>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-2">
                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">No lessons available yet</p>
                            <p class="text-sm text-gray-400">Check back later for course content</p>
                        </div>
                    @endif
                </div>

                <!-- Live Sessions -->
                @if($course->liveSessions->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Live Sessions</h2>
                        <div class="space-y-3">
                            @foreach($course->liveSessions as $session)
                                <div class="p-3 border border-gray-200 rounded-lg">
                                    <h3 class="font-medium text-gray-900">{{ $session->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $session->description }}</p>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $session->scheduled_at ? $session->scheduled_at->format('M j, Y g:i A') : 'TBD' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-2">
                @if($showQuiz && $selectedQuiz)
                    <!-- Quiz Display -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $selectedQuiz->title }}</h2>
                            <button wire:click="hideQuiz" 
                                    class="text-gray-500 hover:text-gray-700 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <livewire:quiz-component :quiz="$selectedQuiz" />
                    </div>
                @elseif($this->currentLesson)
                    <!-- Lesson Display -->
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-3xl font-bold text-gray-900">{{ $this->currentLesson->title }}</h2>
                            @if($enrollment)
                                @php
                                    $quiz = $course->quizzes->where('title', 'like', '%' . $this->currentLesson->title . '%')->first();
                                    // Alternative matching if the first one doesn't work
                                    if (!$quiz) {
                                        $quiz = $course->quizzes->where('title', $this->currentLesson->title . ' Quiz')->first();
                                    }
                                @endphp
                                @if($quiz)
                                    <button wire:click="showQuizForLesson({{ $this->currentLesson->id }})"
                                            class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Take Quiz
                                    </button>
                                @endif
                            @endif
                        </div>
                        
                        <!-- YouTube Video Section -->
                        @if($this->currentLesson->youtube_video_id)
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                    Video Tutorial
                                </h3>
                                <div class="relative w-full rounded-xl overflow-hidden shadow-lg" style="padding-bottom: 56.25%;">
                                    <iframe 
                                        class="absolute top-0 left-0 w-full h-full"
                                        src="{{ $this->currentLesson->youtube_embed_url }}"
                                        title="{{ $this->currentLesson->title }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <div class="mt-4 flex items-center text-sm text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Duration: {{ $this->currentLesson->duration ?? 'N/A' }}
                                </div>
                            </div>
                        @endif
                        
                        <!-- Lesson Content -->
                        <div class="prose prose-lg max-w-none">
                            {!! $this->currentLesson->content ?? '<p>Lesson content will be available soon.</p>' !!}
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Welcome to {{ $course->title }}</h3>
                        <p class="text-gray-600 mb-6">Select a lesson from the sidebar to start learning, or wait for course content to be added.</p>
                        @if(!$enrollment)
                            <button wire:click="enroll" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Enroll in Course
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
                {{ session('message') }}
            </div>
        @endif
    </div>
</div>
