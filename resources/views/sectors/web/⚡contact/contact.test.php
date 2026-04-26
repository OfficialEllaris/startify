<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.contact')
        ->assertStatus(200);
});
