<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.services')
        ->assertStatus(200);
});
