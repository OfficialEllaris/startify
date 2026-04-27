<?php

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('user can send assets', function () {
    $user = User::factory()->create();
    $wallet = Wallet::create([
        'user_id' => $user->id,
        'balances' => [
            'bitcoin' => '1.0',
        ],
    ]);

    $this->actingAs($user);

    Livewire::test('sectors::app.wallet')
        ->set('selectedAssetId', 'bitcoin')
        ->set('recipient', '0x12345678901234567890')
        ->set('amount', '0.1')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('view', 'overview');

    $wallet->refresh();
    expect($wallet->balances['bitcoin'])->toBe('0.9');

    expect(Transaction::count())->toBe(1);
    $transaction = Transaction::first();
    expect($transaction->user_id)->toBe($user->id);
    expect($transaction->asset_id)->toBe('bitcoin');
    expect($transaction->amount)->toBe('0.1');
    expect($transaction->recipient_address)->toBe('0x12345678901234567890');
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

    Livewire::test('sectors::app.wallet')
        ->set('selectedAssetId', 'bitcoin')
        ->set('recipient', '0x12345678901234567890')
        ->set('amount', '1.1')
        ->call('send')
        ->assertHasErrors(['amount' => 'Insufficient balance.']);
});
