<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BrowseController;
use App\Http\Controllers\Admin\LabourerController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\AvailabilityTodayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/browse', [BrowseController::class, 'index'])->name('browse');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::redirect('/', '/admin/labourers')->name('home');

        Route::resource('labourers', LabourerController::class);

        Route::get('areas', [AreaController::class, 'index'])->name('areas.index');
        Route::post('areas', [AreaController::class, 'store'])->name('areas.store');
        Route::patch('areas/{area}', [AreaController::class, 'update'])->name('areas.update');

        Route::get('skills', [SkillController::class, 'index'])->name('skills.index');
        Route::post('skills', [SkillController::class, 'store'])->name('skills.store');
        Route::patch('skills/{skill}', [SkillController::class, 'update'])->name('skills.update');

        Route::get('availability/today', [AvailabilityTodayController::class, 'index'])->name('availability.today');
        Route::post('availability/today', [AvailabilityTodayController::class, 'store'])->name('availability.today.store');
    });

require __DIR__.'/auth.php';
