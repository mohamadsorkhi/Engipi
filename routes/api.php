<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SubdomainController;
use App\Http\Controllers\Api\SkillController;

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
    [SkillController::class, 'store']
);