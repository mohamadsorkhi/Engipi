<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProcessController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SkillDomainController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserProfileController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\TicketDepartmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Skill Management
Route::resource('skills', SkillController::class)->except(['show']);

// Project Management
Route::resource('projects', ProjectController::class)->only(['index', 'show', 'destroy']);

// User Management
Route::resource('users', UserController::class)->only(['index', 'show', 'update', 'destroy']);

// Skill Domain Management
Route::resource('domains', SkillDomainController::class)->except(['show', 'create', 'edit']);

// Process Management
Route::resource('processes', ProcessController::class)->except(['show', 'create', 'edit']);

// User Profile Management
Route::resource('profiles', UserProfileController::class)->only(['index', 'destroy']);

// Ticket Departments
Route::resource('ticket-departments', TicketDepartmentController::class)->only(['index', 'store', 'update', 'destroy']);

// Tickets
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/tickets/{ticket}/message', [TicketController::class, 'message'])->name('tickets.message');
Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
