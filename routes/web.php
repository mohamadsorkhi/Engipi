<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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


// صفحه اصلی — new design
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('user.dashboard');
    }
    return view('ep-home');
})->name('root');

// ── New-design preview routes (closure, placeholder data) ──
Route::prefix('ep')->name('ep.')->group(function () {

    Route::get('/dashboard', function () {
        return view('ep-dashboard.index', [
            'myProjectsCount'       => 12,
            'receivedRequestsCount' => 8,
            'matchedCount'          => 4,
            'sentRequestsCount'     => 5,
            'myProjects' => collect([
                (object)['title' => 'شبیه‌سازی سیستم کنترل با MATLAB',  'created_at' => now()],
                (object)['title' => 'تحلیل تنش سازه با ANSYS',           'created_at' => now()->subDays(3)],
                (object)['title' => 'مدل‌سازی داده با Python',           'created_at' => now()->subDays(7)],
            ]),
            'matchedProjects' => collect([
                (object)['title' => 'طراحی مدار قدرت سه‌فاز', 'domains' => collect([(object)['name' => 'مهندسی برق']])],
                (object)['title' => 'بهینه‌سازی خط تولید',   'domains' => collect([(object)['name' => 'مهندسی صنایع']])],
                (object)['title' => 'تحلیل ارتعاشات شفت',    'domains' => collect([(object)['name' => 'مهندسی مکانیک']])],
            ]),
        ]);
    })->name('dashboard');

    Route::get('/matched', function () {
        return view('ep-dashboard.matched', [
            'projects' => collect([
                (object)[
                    'id'          => '1',
                    'title'       => 'طراحی مدار قدرت سه‌فاز برای کارخانه',
                    'description' => 'نیازمند طراحی و شبیه‌سازی مدار قدرت سه‌فاز با در نظر گرفتن حفاظت و کیفیت توان برای یک خط تولید صنعتی.',
                    'created_at'  => now()->subDays(2),
                    'employer'    => (object)['name' => 'شرکت پایا صنعت'],
                    'domains'     => collect([(object)['name' => 'مهندسی برق']]),
                    'processes'   => collect([(object)['name' => 'MATLAB'], (object)['name' => 'ETAP']]),
                ],
                (object)[
                    'id'          => '2',
                    'title'       => 'تحلیل ارتعاشات شفت توربین',
                    'description' => 'تحلیل مودال و ارتعاشات یک شفت توربین گازی و ارائه راهکار کاهش ارتعاش در محدوده دور کاری.',
                    'created_at'  => now()->subDays(5),
                    'employer'    => (object)['name' => 'مهدی رضایی'],
                    'domains'     => collect([(object)['name' => 'مهندسی مکانیک']]),
                    'processes'   => collect([(object)['name' => 'ANSYS'], (object)['name' => 'SolidWorks']]),
                ],
                (object)[
                    'id'          => '3',
                    'title'       => 'بهینه‌سازی چیدمان خط تولید',
                    'description' => 'مدل‌سازی و بهینه‌سازی چیدمان خط تولید با هدف کاهش زمان جابه‌جایی و افزایش بهره‌وری.',
                    'created_at'  => now()->subDays(1),
                    'employer'    => (object)['name' => 'گروه صنعتی آرمان'],
                    'domains'     => collect([(object)['name' => 'مهندسی صنایع']]),
                    'processes'   => collect([(object)['name' => 'Python'], (object)['name' => 'Arena']]),
                ],
                (object)[
                    'id'          => '4',
                    'title'       => 'مدل‌سازی پیش‌بینی مصرف انرژی',
                    'description' => 'ساخت یک مدل یادگیری ماشین برای پیش‌بینی مصرف انرژی ساختمان بر اساس داده‌های تاریخی.',
                    'created_at'  => now()->subDays(4),
                    'employer'    => (object)['name' => 'دانشگاه صنعتی'],
                    'domains'     => collect([(object)['name' => 'مهندسی کامپیوتر']]),
                    'processes'   => collect([(object)['name' => 'Python'], (object)['name' => 'TensorFlow']]),
                ],
            ]),
        ]);
    })->name('matched');

    Route::get('/role-select', function () {
        return view('ep-dashboard.role-select');
    })->name('role-select');

});

// صفحات عمومی
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/terms', fn() => view('pages.terms'))->name('terms');


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