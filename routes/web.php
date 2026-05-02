<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileSelectController;
use App\Http\Controllers\Auth\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

// The main dashboard route, accessible after login
Route::get('/', [DashboardController::class, 'index'])->name('root')->middleware('auth');

// Profile management routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/select', [ProfileSelectController::class, 'index'])->name('profile.select');
    Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::put('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
});

// Admin routes - requires is_admin flag
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    require __DIR__.'/admin.php';
});

// Unified User routes (combines employer + specialist functionality)
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    require __DIR__.'/user.php';
});


//Update User Details
/*Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');*/


