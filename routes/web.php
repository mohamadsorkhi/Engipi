<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\ProfileSelectController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\SkillSelectController;
use App\Http\Controllers\Specialist\SkillSuggestionController;
use App\Http\Controllers\Employer\GuestProjectController;
use App\Http\Controllers\Employer\ProjectController as EmployerProjectController;
use App\Http\Controllers\PublicProjectController;

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

    Route::post('/skill-suggestions', [SkillSuggestionController::class, 'store'])
        ->name('skill-suggestions.store');

});


// صفحه اصلی
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }

    return view('landing', [
        'projectsCount' => \App\Models\Project::count(),
        'specialistsCount' => \App\Models\UserProfile::where('type', 'specialist')->count(),
        'employersCount' => \App\Models\UserProfile::where('type', 'employer')->count(),
        'domainsCount' => \App\Models\SkillDomain::count(),
        'domains' => \App\Models\SkillDomain::withCount('subdomains')->orderBy('name')->get(),
    ]);
})->name('root');

// Landing Page V2 preview (kept separate from the production homepage)
Route::view('/landing-v2', 'landing-v2')->name('landing.v2');
Route::view('/landing-v2-a', 'landing-v2-a')->name('landing.v2.a');
Route::view('/landing-v2-b', 'landing-v2-b')->name('landing.v2.b');
Route::view('/landing-v2-c', 'landing-v2-c')->name('landing.v2.c');

// صفحات عمومی
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/terms', fn() => view('pages.terms'))->name('terms');

Route::get('/share-test-2026', fn() => view('pages.share-test-2026'));
Route::get('/projects/{project}', [PublicProjectController::class, 'show'])->name('projects.show');


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
