<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\DashboardController;
use App\Http\Controllers\Employer\ProjectController;
use App\Http\Controllers\Employer\RequestController;

/*
|--------------------------------------------------------------------------
| Employer Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ProjectController::class);

// Request Management
Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
Route::post('/requests/{request}/accept', [RequestController::class, 'accept'])->name('requests.accept');
Route::post('/requests/{request}/reject', [RequestController::class, 'reject'])->name('requests.reject');
Route::post('/requests/{request}/revert-reject', [RequestController::class, 'revertReject'])->name('requests.revert-reject');
