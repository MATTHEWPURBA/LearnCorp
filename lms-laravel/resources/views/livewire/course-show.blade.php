<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $course->title }}</h1>
                    <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span>Instructor: {{ $course->instructor->name }}</span>
                        <span>•</span>
                        <span>{{ $course->lessons->count() }} lessons</span>
                        <span>•</span>
                        <span>{{ $course->liveSessions->count() }} live sessions</span>
                    </div>
                </div>
                <div class="ml-6">
                    @if($enrollment)
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-medium">
                            Enrolled
                        </div>
                    @else
                        <button wire:click="enroll" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                            Enroll Now
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lessons Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Course Content</h2>
                    
                    @if($course->lessons->count() > 0)
                        <div class="space-y-2">
                            @foreach($course->lessons as $lesson)
                                <button wire:click="selectLesson({{ $lesson->id }})"
                                        class="w-full text-left p-3 rounded-lg border transition-colors {{ $currentLessonId == $lesson->id ? 'bg-blue-50 border-blue-200 text-blue-900' : 'hover:bg-gray-50 border-gray-200' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h3 class="font-medium">{{ $lesson->title }}</h3>
                                                @if($lesson->youtube_video_id)
                                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $lesson->description ?? 'No description' }}</p>
                                        </div>
                                        <div class="text-xs text-gray-400 ml-2">
                                            {{ $lesson->duration ?? 'N/A' }}
                                        </div>
                                    </div>
                                </button>
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
                @if($this->currentLesson)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $this->currentLesson->title }}</h2>
                        
                        <!-- YouTube Video Section -->
                        @if($this->currentLesson->youtube_video_id)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Video Tutorial</h3>
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <iframe 
                                        class="absolute top-0 left-0 w-full h-full rounded-lg"
                                        src="{{ $this->currentLesson->youtube_embed_url }}"
                                        title="{{ $this->currentLesson->title }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                        </svg>
                                        Duration: {{ $this->currentLesson->duration ?? 'N/A' }}
                                    </span>
                                </p>
                            </div>
                        @endif
                        
                        <!-- Lesson Content -->
                        <div class="prose max-w-none">
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
