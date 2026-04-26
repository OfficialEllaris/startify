<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.terms')
        ->assertStatus(200);
});
