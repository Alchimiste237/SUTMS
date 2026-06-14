<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('timetable', \App\Livewire\StudentTimetableLookup::class)->name('timetable');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('academic-structure', \App\Livewire\AcademicStructureManager::class)->name('academic-structure');
    Route::get('academic-time', \App\Livewire\AcademicTimeManager::class)->name('academic-time');
    Route::get('teaching-assignments', \App\Livewire\TeachingAssignmentManager::class)->name('teaching-assignments');
    Route::get('my-availability', \App\Livewire\TeacherAvailabilityManager::class)->name('my-availability');
    Route::get('my-timetable', \App\Livewire\TeacherTimetable::class)->name('my-timetable');
    Route::get('schedule-management', \App\Livewire\ScheduleManager::class)->name('schedule-management');
    Route::get('teacher-management', \App\Livewire\TeacherManager::class)->name('teacher-management');
});


require __DIR__.'/settings.php';
