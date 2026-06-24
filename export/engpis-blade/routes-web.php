<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| EngPis Blueprint — routes
|--------------------------------------------------------------------------
| این بلوک را به انتهای routes/web.php پروژه‌ات اضافه کن.
| اگر روتی با همین آدرس از قبل داری، نام یا آدرس را تغییر بده تا تداخل نشود.
*/

Route::view('/',                 'landing')->name('engpis.landing');
Route::view('/auth',             'auth')->name('engpis.auth');
Route::view('/dashboard',        'dashboard')->name('engpis.dashboard');
Route::view('/projects/create',  'projects.create')->name('engpis.projects.create');
Route::view('/projects/show',    'projects.show')->name('engpis.projects.show');
Route::view('/profile',          'profile')->name('engpis.profile');
Route::view('/requests',         'requests')->name('engpis.requests');
Route::view('/tickets',          'tickets')->name('engpis.tickets');
