<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Lesson Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                <p class="mt-2 text-gray-600">{{ $lesson->description }}</p>
            </div>

            <!-- Video Player -->
            @if($lesson->youtube_video_id)
                <div class="mb-6">
                    <div class="relative w-full h-0 pb-[56.25%] bg-gray-900 rounded-lg overflow-hidden">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="{{ $lesson->youtube_embed_url }}"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            @endif

            <!-- Lesson Content -->
            @if($lesson->asset_path)
                <div class="mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4">Lesson Materials</h3>
                        <a href="{{ $lesson->asset_url }}" 
                           target="_blank" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Materials
                        </a>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-4 mb-6">
                @if($enrollment)
                    <button wire:click="markAsCompleted" 
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Mark as Completed
                    </button>
                @endif
                
                @if($lesson->quiz)
                    <button wire:click="toggleQuiz" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        {{ $showQuiz ? 'Hide' : 'Take' }} Quiz
                    </button>
                @endif
            </div>

            <!-- Quiz Section -->
            @if($showQuiz && $lesson->quiz)
                <div class="bg-white rounded-lg shadow-md p-6">
                    @livewire('quiz-component', ['quiz' => $lesson->quiz])
                </div>
            @endif

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- AI Chat -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">AI Assistant</h3>
                @livewire('ai-chat')
            </div>

            <!-- Course Progress -->
            @if($enrollment)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Your Progress</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Course Progress</span>
                            <span>{{ $enrollment->progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
