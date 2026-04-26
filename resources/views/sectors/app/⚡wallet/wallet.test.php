<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.wallet')
        ->assertStatus(200);
});
