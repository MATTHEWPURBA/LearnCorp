<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CourseCatalog;
use App\Livewire\CourseShow;
use App\Livewire\LessonPlayer;
use App\Livewire\LiveSession;

// Public routes
Route::get('/', function () {
    return redirect()->route('courses.index');
});

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Course routes
    Route::get('/courses', CourseCatalog::class)->name('courses.index');
    Route::get('/courses/{course}', CourseShow::class)->name('courses.show');
    Route::get('/lessons/{lesson}', LessonPlayer::class)->name('lessons.show');
    Route::get('/live-sessions/{session}', LiveSession::class)->name('live-sessions.show');
});
