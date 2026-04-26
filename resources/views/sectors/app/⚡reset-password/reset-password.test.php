<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.reset-password')
        ->assertStatus(200);
});
