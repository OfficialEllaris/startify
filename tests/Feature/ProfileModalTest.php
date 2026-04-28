<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('manager can see and update wallet addresses in profile modal', function () {
    $manager = User::factory()->manager()->create();
    $manager->wallet()->create([
        'addresses' => [
            'bitcoin' => 'old-btc-address',
        ],
    ]);

    $this->actingAs($manager);

    Livewire::test('profile-modal')
        ->assertSee('Profile')
        ->assertSee('Security')
        ->assertSee('Wallet') // Verify tab exists
        ->set('wallet_addresses.bitcoin', 'new-btc-address')
        ->call('updateWalletAddresses')
        ->assertHasNoErrors();

    $manager->refresh();
    expect($manager->wallet->addresses['bitcoin'])->toBe('new-btc-address');
});

test('client cannot see wallet tab in profile modal', function () {
    $client = User::factory()->create(); // Default is client

    $this->actingAs($client);

    Livewire::test('profile-modal')
        ->assertSee('Profile')
        ->assertSee('Security')
        ->assertDontSee('Wallet'); // Verify tab does NOT exist
});
