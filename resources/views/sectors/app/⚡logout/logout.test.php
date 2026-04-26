<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.logout')
        ->assertStatus(200);
});
