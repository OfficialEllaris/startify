<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('profile-modal')
        ->assertStatus(200);
});
