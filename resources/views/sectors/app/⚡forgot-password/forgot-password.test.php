<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.forgot-password')
        ->assertStatus(200);
});
