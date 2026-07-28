<?php

use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SubdomainController;
use App\Http\Controllers\Api\UserSkillController;
use Illuminate\Support\Facades\Route;

Route::get(
    '/subdomains/{domainId}',
    [SubdomainController::class, 'index']
);

Route::get(
    '/skills/{subdomain}',
    [SkillController::class, 'index']
);

Route::post(
    '/user-skill',
    [UserSkillController::class, 'store']
)->middleware('auth:sanctum');
