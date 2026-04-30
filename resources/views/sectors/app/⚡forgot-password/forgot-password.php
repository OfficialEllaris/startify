<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Forgot Password')] class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public string $status = '';

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::broker()->sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->status = __($status);
            $this->reset('email');
        } else {
            $this->addError('email', __($status));
        }
    }
};
