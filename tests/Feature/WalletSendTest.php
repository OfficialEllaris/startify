<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
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
});

test('user can send assets', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'balances' => [
            'bitcoin' => '1.0',
        ],
    ]);

    $this->actingAs($user);

    // Amount is USD; at $100k/BTC, $10_000 => 0.1 BTC deducted
    Livewire::test('sectors::app.wallet')
        ->set('selectedAssetId', 'bitcoin')
        ->set('recipient', '0x1234567890123456789012345678901234567890')
        ->set('amount', '10000')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('view', 'overview');

    $wallet->refresh();
    expect((float) $wallet->balances['bitcoin'])->toBe(0.9);

    expect(Transaction::count())->toBe(1);
    $transaction = Transaction::first();
    expect($transaction->user_id)->toBe($user->id);
    expect($transaction->asset_id)->toBe('bitcoin');
    expect((float) $transaction->amount)->toBe(0.1);
    expect($transaction->recipient_address)->toBe('0x1234567890123456789012345678901234567890');
    expect($transaction->type)->toBe('send');
});

test('user cannot send more than balance', function () {
    $user = User::factory()->create();
    Wallet::create([
        'user_id' => $user->id,
        'balances' => [
            'bitcoin' => '1.0',
        ],
    ]);

    $this->actingAs($user);

    // More than 1 BTC at $100k/BTC => need more than $100_000 USD
    Livewire::test('sectors::app.wallet')
        ->set('selectedAssetId', 'bitcoin')
        ->set('recipient', '0x1234567890123456789012345678901234567890')
        ->set('amount', '100001')
        ->call('send')
        ->assertHasErrors(['amount' => 'Insufficient balance.']);
});
