<?php

use Illuminate\Support\Facades\Route;

/**
 * Root domain routes
 * Handles public-facing pages.
 */
Route::domain(config('app.domain'))->name('web.')
    ->group(base_path('routes/domain/web.php'));

/**
 * App subdomain routes
 * Handles account level features.
 */
Route::domain('app.'.config('app.domain'))->name('app.')
    ->group(base_path('routes/domain/app.php'));
