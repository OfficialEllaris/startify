<?php

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('receive view shows manager deposit address from wallet addresses JSON', function () {
    Http::fake([
        'api.coingecko.com/*' => Http::response([
            [
                'id' => 'bitcoin',
                'name' => 'Bitcoin',
                'symbol' => 'btc',
                'current_price' => 50_000,
                'price_change_percentage_24h' => 1.5,
                'image' => 'https://example.com/btc.png',
            ],
        ], 200),
    ]);

    Cache::forget('coingecko_assets_v2');

    $manager = User::factory()->manager()->create();
    $manager->wallet()->create([
        'addresses' => [
            'bitcoin' => 'bc1testdepositaddressunique',
        ],
    ]);

    $client = User::factory()->create();
    Wallet::create([
        'user_id' => $client->id,
        'balances' => [
            'bitcoin' => '1.0',
        ],
    ]);

    $this->actingAs($client);

    Livewire::test('sectors::app.wallet')
        ->set('view', 'receive')
        ->set('selectedAssetId', 'bitcoin')
        ->assertSee('bc1testdepositaddressunique');
});
