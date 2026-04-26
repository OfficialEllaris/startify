<?php

use Illuminate\Support\Facades\Route;

/**
 * Web (public-facing) routes
 *
 * These routes form the main website accessible via the root domain.
 * They are primarily informational and do not require authentication.
 * Includes marketing pages, product information, and legal documentation.
 */
Route::livewire('/', 'sectors::web.home')->name('home');

/**
 * About page
 * Provides information about the application, team, or organization.
 */
Route::livewire('/about', 'sectors::web.about')->name('about');

/**
 * Pricing page
 * Displays available plans, pricing tiers, and subscription options.
 */
Route::livewire('/pricing', 'sectors::web.pricing')->name('pricing');

/**
 * Services page
 * Describes the core services or features offered.
 */
Route::livewire('/services', 'sectors::web.services')->name('services');

/**
 * Contact page
 * Allows users to reach out via forms or contact information.
 */
Route::livewire('/contact', 'sectors::web.contact')->name('contact');

/**
 * Terms of service page
 * Outlines usage rules, responsibilities, and legal terms.
 */
Route::livewire('/terms', 'sectors::web.terms')->name('terms');

/**
 * Privacy policy page
 * Explains data usage, storage, and user privacy rights.
 */
Route::livewire('/privacy', 'sectors::web.privacy')->name('privacy');
