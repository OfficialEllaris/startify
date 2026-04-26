<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.wallets')
        ->assertStatus(200);
});
