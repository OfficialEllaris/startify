<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.traders')
        ->assertStatus(200);
});
