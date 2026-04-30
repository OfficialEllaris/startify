<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.clients')
        ->assertStatus(200);
});
