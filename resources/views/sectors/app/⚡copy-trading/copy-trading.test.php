<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.copy-trading')
        ->assertStatus(200);
});
