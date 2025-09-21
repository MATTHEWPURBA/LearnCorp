<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
            <svg class="w-8 h-8 mr-3 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            Leaderboard
        </h2>
        
        @if($course)
            <div class="text-sm text-gray-600">
                {{ $course->title }}
            </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-4 mb-6">
        <div class="flex bg-gray-100 rounded-lg p-1">
            <button wire:click="setTimeframe('week')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $timeframe === 'week' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                This Week
            </button>
            <button wire:click="setTimeframe('month')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $timeframe === 'month' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                This Month
            </button>
            <button wire:click="setTimeframe('all')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $timeframe === 'all' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                All Time
            </button>
        </div>

        <div class="flex bg-gray-100 rounded-lg p-1">
            <button wire:click="setType('points')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'points' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Points
            </button>
            <button wire:click="setType('badges')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'badges' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Badges
            </button>
            <button wire:click="setType('quizzes')" 
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $type === 'quizzes' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                Quizzes
            </button>
        </div>
    </div>

    <!-- Leaderboard List -->
    <div class="space-y-3">
        @forelse($this->leaderboardData as $index => $user)
            <div class="flex items-center p-4 rounded-lg {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-200' : 'bg-gray-50 hover:bg-gray-100' }} transition-colors">
                <!-- Rank -->
                <div class="flex-shrink-0 w-12 text-center">
                    @if($index === 0)
                        <div class="text-3xl">🥇</div>
                    @elseif($index === 1)
                        <div class="text-3xl">🥈</div>
                    @elseif($index === 2)
                        <div class="text-3xl">🥉</div>
                    @else
                        <div class="text-2xl font-bold text-gray-600">#{{ $index + 1 }}</div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1 ml-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="flex items-center space-x-6">
                    @if($type === 'points')
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $course ? $user->course_points : $user->total_points }}</div>
                            <div class="text-xs text-gray-600">Points</div>
                        </div>
                    @elseif($type === 'badges')
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $user->badges_count }}</div>
                            <div class="text-xs text-gray-600">Badges</div>
                        </div>
                    @elseif($type === 'quizzes')
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $user->attempts()->count() }}</div>
                            <div class="text-xs text-gray-600">Quizzes</div>
                        </div>
                    @endif

                    <div class="text-center">
                        <div class="text-lg font-semibold text-gray-600">{{ $user->achievements_count }}</div>
                        <div class="text-xs text-gray-600">Achievements</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Data Available</h3>
                <p class="text-gray-600">No users found for the selected criteria.</p>
            </div>
        @endforelse
    </div>

    @if($this->leaderboardData->count() > 0)
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Showing top {{ $this->leaderboardData->count() }} users
                @if($timeframe !== 'all')
                    in the last {{ $timeframe === 'week' ? '7 days' : '30 days' }}
                @endif
            </p>
        </div>
    @endif
</div>