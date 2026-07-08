<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Engipi Blueprint — routes
|--------------------------------------------------------------------------
| این بلوک را به انتهای routes/web.php پروژه‌ات اضافه کن.
| اگر روتی با همین آدرس از قبل داری، نام یا آدرس را تغییر بده تا تداخل نشود.
*/

Route::view('/',                 'landing')->name('engipi.landing');
Route::view('/auth',             'auth')->name('engipi.auth');
Route::view('/dashboard',        'dashboard')->name('engipi.dashboard');
Route::view('/projects/create',  'projects.create')->name('engipi.projects.create');
Route::view('/projects/show',    'projects.show')->name('engipi.projects.show');
Route::view('/profile',          'profile')->name('engipi.profile');
Route::view('/requests',         'requests')->name('engipi.requests');
Route::view('/tickets',          'tickets')->name('engipi.tickets');
