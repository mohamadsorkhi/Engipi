<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileSelectController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\SkillSelectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

// ذخیره مهارت‌ها
Route::post('/save-skills', [SkillSelectController::class, 'saveSkills']);

// صفحه تست
Route::get('/test', [SkillSelectController::class, 'index']);


// صفحه اصلی بعد از لاگین
Route::get('/', [DashboardController::class, 'index'])
    ->name('root')
    ->middleware('auth');


// مدیریت پروفایل
Route::middleware(['auth'])->group(function () {

    Route::get('/profile/select', [ProfileSelectController::class, 'index'])
        ->name('profile.select');

    Route::get('/profiles', [ProfileController::class, 'index'])
        ->name('profiles.index');

    Route::post('/profiles', [ProfileController::class, 'store'])
        ->name('profiles.store');

    Route::put('/profiles/{profile}', [ProfileController::class, 'update'])
        ->name('profiles.update');
});


// مسیرهای ادمین
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        require __DIR__.'/admin.php';

});


// مسیرهای کاربر
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        require __DIR__.'/user.php';

});