<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-all-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "All caches have been cleared!";
});

/*Route::get('/storage-link', function () {
    // public_html
    $publicRoot = dirname(base_path()); // چون base_path() = public_html/core

    // مسیر لینک در public_html
    $link   = $publicRoot . '/storage';
    // مسیر مقصد (target) داخل core
    $target = storage_path('app/public'); // public_html/core/storage/app/public

    // اطمینان از وجود مقصد
    if (!is_dir($target)) {
        @mkdir($target, 0755, true);
    }

    // اگر قبلاً وجود دارد
    if (is_link($link) || is_dir($link)) {
        return 'public_html/storage exists.';
    }

    // تلاش برای ساخت symlink
    if (function_exists('symlink')) {
        if (@symlink($target, $link)) {
            return 'symlink created: ' . $link . ' -> ' . $target;
        }
    }

    // هاست‌هایی که symlink را بلاک می‌کنند
    return 'failed: cannot create symlink. link=' . $link . ' target=' . $target;
});*/
