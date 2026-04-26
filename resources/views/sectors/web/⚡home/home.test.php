<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.home')
        ->assertStatus(200);
});
