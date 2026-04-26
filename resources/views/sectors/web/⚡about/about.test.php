<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.about')
        ->assertStatus(200);
});
