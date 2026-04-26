<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    /**
     * Login page
     * Allows users to authenticate into the application.
     */
    Route::livewire('/login', 'sectors::app.login')->name('login');

    /**
     * Forgot password request page
     * Used to initiate password reset via email.
     */
    Route::livewire('/forgot-password', 'sectors::app.forgot-password')->name('forgot-password');

    /**
     * Reset password page
     * Handles password update using reset token.
     */
    Route::livewire('/reset-password/{token}', 'sectors::app.reset-password')->name('password.reset');
});

/**
 * Onboarding page
 * Guides new or incomplete users through initial setup.
 */
Route::livewire('/onboarding', 'sectors::app.onboarding')->name('onboarding');

/**
 * Email verification page
 * Prompts user to verify their email address.
 */
Route::livewire('/verify-email', 'sectors::app.verify-email')->name('verify-email');

/**
 * Authenticated routes
 * Only accessible to logged-in users.
 */
Route::middleware('auth')->group(function () {

    /**
     * User dashboard
     * Main entry point after authentication.
     */
    Route::livewire('/', 'sectors::app.dashboard')->name('dashboard');

    /**
     * Wallet page
     * Crypto Assets for clients.
     */
    Route::livewire('/wallet', 'sectors::app.wallet')->name('wallet');

    /**
     * Wallets page
     * View all wallets for managers.
     */
    Route::livewire('/wallets', 'sectors::app.wallets')->name('wallets');

    /**
     * Logout route
     * Terminates the user session.
     */
    Route::livewire('/logout', 'sectors::app.logout')->name('logout');

});
