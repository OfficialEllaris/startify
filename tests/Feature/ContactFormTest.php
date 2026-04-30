<?php

use App\Mail\ContactNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('can submit the contact page form', function () {
    Mail::fake();

    Livewire::test('sectors::web.contact')
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('subject', 'Test Subject')
        ->set('message', 'Test Message')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertStatus(200);

    Mail::assertSent(ContactNotification::class, function ($mail) {
        return $mail->hasTo(config('mail.from.address')) &&
               $mail->data['name'] === 'John Doe' &&
               $mail->data['email'] === 'john@example.com';
    });
});
