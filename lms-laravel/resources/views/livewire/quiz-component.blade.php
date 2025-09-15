<div>
    <div class="mb-4">
        <h3 class="text-lg font-semibold">{{ $quiz->title }}</h3>
        <p class="text-sm text-gray-600">Passing Score: {{ $quiz->pass_score }}%</p>
    </div>

    @if(!$submitted)
        <form wire:submit.prevent="submitQuiz">
            <div class="space-y-6">
                @foreach($quiz->questions as $question)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium mb-3">{{ $loop->iteration }}. {{ $question->prompt }}</h4>
                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input 
                                        type="radio" 
                                        wire:model="answers.{{ $question->id }}" 
                                        value="{{ $option->id }}"
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                    >
                                    <span class="text-sm">{{ $option->text }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('answers.' . $question->id)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Submit Quiz
                </button>
            </div>
        </form>
    @else
        <!-- Quiz Results -->
        <div class="space-y-4">
            <div class="text-center">
                <div class="text-4xl font-bold {{ $attempt->isPassing() ? 'text-green-600' : 'text-red-600' }}">
                    {{ $attempt->score }}%
                </div>
                <p class="text-lg {{ $attempt->isPassing() ? 'text-green-600' : 'text-red-600' }}">
                    {{ $attempt->isPassing() ? 'Congratulations! You passed!' : 'You need to retake this quiz.' }}
                </p>
            </div>

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

            <div class="mt-6 flex justify-center">
                <button 
                    wire:click="retakeQuiz" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Retake Quiz
                </button>
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
