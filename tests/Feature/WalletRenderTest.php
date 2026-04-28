<?php

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('wallet livewire renders for an authenticated user', function () {
    Http::fake([
        'api.coingecko.com/*' => Http::response([
            [
                'id' => 'bitcoin',
                'name' => 'Bitcoin',
                'symbol' => 'btc',
                'current_price' => 100_000,
                'price_change_percentage_24h' => 0,
                'image' => 'https://example.com/btc.png',
            ],
        ], 200),
    ]);

    Cache::forget('coingecko_assets_v2');

    $user = User::factory()->create();
    Wallet::create([
        'user_id' => $user->id,
        'balances' => ['bitcoin' => '1.0'],
    ]);

    $this->actingAs($user);

    Livewire::test('sectors::app.wallet')
        ->assertSuccessful();
});
