<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.dashboard')
        ->assertStatus(200);
});
