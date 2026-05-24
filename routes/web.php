<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileSelectController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\SkillSelectController;
use App\Http\Controllers\Employer\GuestProjectController;
use App\Http\Controllers\Employer\ProjectController as EmployerProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();


// Pre-registration employer project form (guest only)
Route::middleware(['guest'])->group(function () {
    Route::get('/post-project', [GuestProjectController::class, 'index'])->name('guest.project');
    Route::post('/post-project', [GuestProjectController::class, 'store'])->name('guest.project.store');
});


// انتخاب مهارت (specialist only)
Route::middleware(['auth', 'active_role:specialist'])->group(function () {

    Route::get(
        '/skill-select',
        [SkillSelectController::class, 'index']
    )->name('skill.select');

    Route::post(
        '/save-user-skills',
        [SkillSelectController::class, 'saveSkills']
    )->name('skill.save');

});


// صفحه اصلی بعد از لاگین
Route::get(
'/',
[DashboardController::class,'index']
)
->name('root')
->middleware(['auth', 'active_role']);


// مدیریت پروفایل
Route::middleware(['auth'])
->group(function () {

    Route::get(
    '/profile/select',
    [ProfileSelectController::class,'index']
    )->name('profile.select');

    Route::post(
    '/profile/activate',
    [ProfileSelectController::class,'activate']
    )->name('profile.activate');


    Route::get(
    '/profiles',
    [ProfileController::class,'index']
    )->name('profiles.index');


    Route::post(
    '/profiles',
    [ProfileController::class,'store']
    )->name('profiles.store');


    Route::put(
    '/profiles/{profile}',
    [ProfileController::class,'update']
    )->name('profiles.update');

});


// مسیرهای ادمین
Route::middleware(['auth','admin'])
->prefix('admin')
->name('admin.')
->group(function(){

    require __DIR__.'/admin.php';

});


// ثبت پروژه توسط کارفرما
Route::middleware(['auth', 'active_role:employer'])
    ->prefix('employer')
    ->name('employer.')
    ->group(function () {
        Route::get('/projects/create', [EmployerProjectController::class, 'createSimple'])->name('projects.create');
        Route::post('/projects', [EmployerProjectController::class, 'storeSimple'])->name('projects.store');
    });


// مسیرهای کاربر
Route::middleware(['auth'])
->prefix('user')
->name('user.')
->group(function(){

    require __DIR__.'/user.php';

});