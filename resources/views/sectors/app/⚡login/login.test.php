<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.login')
        ->assertStatus(200);
});
