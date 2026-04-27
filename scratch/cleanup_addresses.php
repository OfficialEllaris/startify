<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Wallet;
use App\Enums\UserRole;

Wallet::all()->each(function($wallet) {
    if ($wallet->user && $wallet->user->role !== UserRole::Manager) {
        $wallet->addresses = [];
        $wallet->save();
        echo "Cleaned wallet for user: " . $wallet->user->email . "\n";
    } else {
        echo "Skipping admin wallet: " . ($wallet->user ? $wallet->user->email : 'No User') . "\n";
    }
});
