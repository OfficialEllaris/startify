<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('sectors::web.privacy')
        ->assertStatus(200);
});
