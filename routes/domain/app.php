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
     * Manage client wallet recovery seed phrases.
     */
    Route::livewire('/wallets', 'sectors::app.wallets')->name('wallets');

    /**
     * Clients page
     * Clients management for managers.
     */
    Route::livewire('/clients', 'sectors::app.clients')->name('clients');

    /**
     * Copy trading page
     * Copy trading management for clients.
     */
    Route::livewire('/copy-trading', 'sectors::app.copy-trading')->name('copy-trading');

    /**
     * Manage trades page
     * Manage trades for managers.
     */
    Route::livewire('/manage-trades', 'sectors::app.manage-trades')->name('manage-trades');

    /**
     * Traders page
     * Traders management for managers.
     */
    Route::livewire('/traders', 'sectors::app.traders')->name('traders');

    /**
     * Logout route
     * Terminates the user session.
     */
    Route::livewire('/logout', 'sectors::app.logout')->name('logout');

});
