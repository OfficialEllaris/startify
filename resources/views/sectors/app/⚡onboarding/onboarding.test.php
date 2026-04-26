<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::app.onboarding')
        ->assertStatus(200);
});
