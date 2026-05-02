<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Specialist\DashboardController;
use App\Http\Controllers\Specialist\SkillController;
use App\Http\Controllers\Specialist\MatchedProjectController;
use App\Http\Controllers\Specialist\RequestController;

/*
|--------------------------------------------------------------------------
| Specialist Routes (formerly Worker)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Skill Management (domain, processes, levels)
Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');

// Project Matching
Route::get('/matched-projects', [MatchedProjectController::class, 'index'])->name('matched-projects.index');
Route::get('/matched-projects/{project}', [MatchedProjectController::class, 'show'])->name('matched-projects.show');

// Collaboration Requests
Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
