<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.manage-trades')
        ->assertStatus(200);
});
