<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Employer\ProjectController;
use App\Http\Controllers\Employer\RequestController as EmployerRequestController;
use App\Http\Controllers\Specialist\SkillController;
use App\Http\Controllers\Specialist\MatchedProjectController;
use App\Http\Controllers\Specialist\RequestController as SpecialistRequestController;
use App\Http\Controllers\User\TicketController;

/*
|--------------------------------------------------------------------------
| User Routes (Unified Employer + Specialist)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Projects Management (as employer) - uses existing Employer controllers
Route::resource('projects', ProjectController::class);

// Skills Management (as specialist) - uses existing Specialist controllers
Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');

// Matched Projects (as specialist) - uses existing Specialist controllers
Route::get('/matched-projects', [MatchedProjectController::class, 'index'])->name('matched-projects.index');
Route::get('/matched-projects/{project}', [MatchedProjectController::class, 'show'])->name('matched-projects.show');

// Requests - Employer (received)
Route::get('/requests/received', [EmployerRequestController::class, 'index'])->name('requests.received');
Route::post('/requests/{request}/accept', [EmployerRequestController::class, 'accept'])->name('requests.accept');
Route::post('/requests/{request}/reject', [EmployerRequestController::class, 'reject'])->name('requests.reject');
Route::post('/requests/{request}/revert', [EmployerRequestController::class, 'revertReject'])->name('requests.revert');

// Requests - Specialist (sent)
Route::get('/requests/sent', [SpecialistRequestController::class, 'index'])->name('requests.sent');
Route::post('/requests', [SpecialistRequestController::class, 'store'])->name('requests.store');

// Tickets
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets/{ticket}/message', [TicketController::class, 'message'])->name('tickets.message');
Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
