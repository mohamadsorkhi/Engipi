<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Worker\DashboardController;
use App\Http\Controllers\Worker\SkillController;
use App\Http\Controllers\Worker\MatchedProjectController;
use App\Http\Controllers\Worker\RequestController;

/*
|--------------------------------------------------------------------------
| Worker Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Skill Management
Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

// Project Matching
Route::get('/matched-projects', [MatchedProjectController::class, 'index'])->name('matched-projects.index');
Route::get('/matched-projects/{project}', [MatchedProjectController::class, 'show'])->name('matched-projects.show');


// Collaboration Requests
Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');
