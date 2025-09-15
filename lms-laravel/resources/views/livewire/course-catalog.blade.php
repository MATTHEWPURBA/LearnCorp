<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Course Catalog</h1>
        <p class="mt-2 text-gray-600">Discover and enroll in our comprehensive learning programs</p>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <div class="max-w-md">
            <input 
                type="text" 
                wire:model.live="search" 
                placeholder="Search courses..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                @if($course->thumbnail)
                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $course->description }}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">By {{ $course->instructor->name }}</span>
                        <span class="text-sm text-gray-500">{{ $course->enrollments->count() }} students</span>
                    </div>
                    
                    <div class="flex gap-2">
                        @if(auth()->user()->isEnrolledIn($course))
                            <a href="{{ route('courses.show', $course) }}" 
                               class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-700 transition-colors">
                                Continue Learning
                            </a>
                        @else
                            <button wire:click="enroll({{ $course->id }})" 
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Enroll Now
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="text-gray-500 text-lg">No courses found matching your search.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $courses->links() }}
    </div>
</div>
