<div class="max-w-4xl mx-auto">
    <!-- Quiz Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold">{{ $quiz->title }}</h3>
                <p class="text-blue-100 mt-1">Passing Score: {{ $quiz->pass_score }}%</p>
            </div>
            @if(!$submitted)
                <div class="text-right">
                    <div class="text-sm text-blue-100">Time Elapsed</div>
                    <div class="text-2xl font-mono" x-data="{ time: 0 }" x-init="setInterval(() => time++, 1000)">
                        <span x-text="Math.floor(time / 60).toString().padStart(2, '0')"></span>:<span x-text="(time % 60).toString().padStart(2, '0')"></span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if(!$submitted)
        <!-- Quiz Form -->
        <form wire:submit.prevent="submitQuiz">
            <div class="space-y-6">
                @foreach($quiz->questions as $question)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-start">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full mr-3 mt-0.5">
                                {{ $loop->iteration }}
                            </span>
                            {{ $question->prompt }}
                        </h4>
                        <div class="space-y-3 ml-8">
                            @foreach($question->options as $option)
                                <label class="flex items-center space-x-3 cursor-pointer group hover:bg-gray-50 p-3 rounded-lg transition-colors">
                                    <input 
                                        type="radio" 
                                        wire:model="answers.{{ $question->id }}" 
                                        value="{{ $option->id }}"
                                        class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                    >
                                    <span class="text-gray-700 group-hover:text-gray-900 transition-colors">{{ $option->text }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('answers.' . $question->id)
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    {{ $quiz->questions->count() }} questions • {{ $quiz->pass_score }}% to pass
                </div>
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    Submit Quiz
                </button>
            </div>
        </form>
    @else
        <!-- Quiz Results -->
        <div class="space-y-6">
            <!-- Score Display -->
            <div class="text-center bg-gradient-to-r {{ $attempt->isPassing() ? 'from-green-500 to-emerald-600' : 'from-red-500 to-pink-600' }} rounded-xl p-8 text-white">
                <div class="text-6xl font-bold mb-2">
                    {{ $attempt->score }}%
                </div>
                <p class="text-xl mb-4">
                    {{ $attempt->isPassing() ? '🎉 Congratulations! You passed!' : '😔 You need to retake this quiz.' }}
                </p>
                <div class="text-sm opacity-90">
                    Completed in {{ gmdate('i:s', $timeSpent) }}
                </div>
            </div>

            <!-- Gamification Results -->
            @if($pointsEarned > 0 || count($badgesEarned) > 0 || count($achievements) > 0)
                <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-6 text-white">
                    <h4 class="text-xl font-bold mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Rewards Earned!
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Points -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">{{ $pointsEarned }}</div>
                            <div class="text-sm opacity-90">Points Earned</div>
                        </div>
                        
                        <!-- Badges -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">{{ count($badgesEarned) }}</div>
                            <div class="text-sm opacity-90">New Badges</div>
                        </div>
                        
                        <!-- Achievements -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold">{{ count($achievements) }}</div>
                            <div class="text-sm opacity-90">Achievements</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Badges Earned -->
            @if(count($badgesEarned) > 0)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="text-2xl mr-2">🏆</span>
                        New Badges Unlocked!
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($badgesEarned as $badge)
                            <div class="bg-gradient-to-br {{ $badge->color == '#10B981' ? 'from-green-100 to-green-200' : ($badge->color == '#F59E0B' ? 'from-yellow-100 to-yellow-200' : 'from-blue-100 to-blue-200') }} rounded-lg p-4 border-2 border-{{ $badge->color == '#10B981' ? 'green' : ($badge->color == '#F59E0B' ? 'yellow' : 'blue') }}-300">
                                <div class="text-center">
                                    <div class="text-3xl mb-2">{{ $badge->icon }}</div>
                                    <div class="font-semibold text-gray-900">{{ $badge->name }}</div>
                                    <div class="text-sm text-gray-600 mt-1">{{ $badge->description }}</div>
                                    <div class="text-xs text-gray-500 mt-2">+{{ $badge->points_reward }} points</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Achievements -->
            @if(count($achievements) > 0)
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                    <h5 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <span class="text-2xl mr-2">🎯</span>
                        New Achievements!
                    </h5>
                    <div class="space-y-3">
                        @foreach($achievements as $achievement)
                            <div class="bg-gradient-to-r from-purple-100 to-pink-100 rounded-lg p-4 border border-purple-200">
                                <div class="flex items-center">
                                    <div class="text-2xl mr-3">🏅</div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $achievement->title }}</div>
                                        <div class="text-sm text-gray-600">{{ $achievement->description }}</div>
                                        <div class="text-xs text-purple-600 mt-1">+{{ $achievement->points_earned }} points</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Answer Review -->
            <div class="space-y-4">
                @foreach($quiz->questions as $question)
                    @php
                        $userAnswerId = $attempt->answers[$question->id] ?? null;
                        $userAnswer = $question->options->find($userAnswerId);
                        $correctAnswer = $question->options->where('is_correct', true)->first();
                    @endphp
                    
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium mb-3">{{ $loop->iteration }}. {{ $question->prompt }}</h4>
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <div class="flex items-center space-x-3 p-2 rounded
                                    {{ $option->is_correct ? 'bg-green-100 border border-green-300' : 
                                       ($userAnswer && $userAnswer->id === $option->id && !$option->is_correct ? 'bg-red-100 border border-red-300' : '') }}">
                                    
                                    @if($option->is_correct)
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($userAnswer && $userAnswer->id === $option->id && !$option->is_correct)
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <div class="w-5 h-5 border border-gray-300 rounded"></div>
                                    @endif
                                    
                                    <span class="text-sm {{ $option->is_correct ? 'text-green-800 font-medium' : 
                                                           ($userAnswer && $userAnswer->id === $option->id && !$option->is_correct ? 'text-red-800' : 'text-gray-700') }}">
                                        {{ $option->text }}
                                        @if($option->is_correct)
                                            <span class="text-green-600 font-medium"> (Correct)</span>
                                        @elseif($userAnswer && $userAnswer->id === $option->id && !$option->is_correct)
                                            <span class="text-red-600 font-medium"> (Your Answer)</span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <button 
                    wire:click="retakeQuiz" 
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Retake Quiz
                </button>
                
                @if($attempt->isPassing())
                    <a href="{{ route('courses.show', $quiz->course) }}" 
                       class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Continue Course
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>
