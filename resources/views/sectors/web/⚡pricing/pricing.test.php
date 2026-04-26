<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.pricing')
        ->assertStatus(200);
});
