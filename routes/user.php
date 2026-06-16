<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Employer\ProjectController;
use App\Http\Controllers\Employer\RequestController as EmployerRequestController;
use App\Http\Controllers\Specialist\SkillController;
use App\Http\Controllers\Specialist\MatchedProjectController;
use App\Http\Controllers\Specialist\RequestController as SpecialistRequestController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\User\MessagesController;

/*
|--------------------------------------------------------------------------
| User Routes (Unified Employer + Specialist)
|--------------------------------------------------------------------------
*/

// Dashboard — any authenticated user with an active role
Route::middleware('active_role')
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// ── Employer-only routes ──────────────────────────────────────────────────
Route::middleware('active_role:employer')->group(function () {

    // Projects Management
    Route::resource('projects', ProjectController::class);

    // Received collaboration requests
    Route::get('/requests/received', [EmployerRequestController::class, 'index'])->name('requests.received');
    Route::post('/requests/{request}/accept', [EmployerRequestController::class, 'accept'])->name('requests.accept');
    Route::post('/requests/{request}/reject', [EmployerRequestController::class, 'reject'])->name('requests.reject');
    Route::post('/requests/{request}/revert', [EmployerRequestController::class, 'revertReject'])->name('requests.revert');

});

// ── Specialist-only routes ────────────────────────────────────────────────
Route::middleware('active_role:specialist')->group(function () {

    // Skills Management
    Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
    Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');

    // Matched Projects
    Route::get('/matched-projects', [MatchedProjectController::class, 'index'])->name('matched-projects.index');
    Route::get('/matched-projects/{project}', [MatchedProjectController::class, 'show'])->name('matched-projects.show');

    // Sent collaboration requests
    Route::get('/requests/sent', [SpecialistRequestController::class, 'index'])->name('requests.sent');
    Route::post('/requests', [SpecialistRequestController::class, 'store'])->name('requests.store');

});

// ── Shared routes (any active role) ──────────────────────────────────────
Route::middleware('active_role')->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/message', [TicketController::class, 'message'])->name('tickets.message');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store');

});
