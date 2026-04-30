<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Reset Password')] class extends Component
{
    #[Url]
    public string $token = '';

    public bool $isValidToken = true;

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public string $status = '';

    public function mount(string $token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');

        $user = User::where('email', $this->email)->first();

        if (! $user || ! Password::broker()->tokenExists($user, $this->token)) {
            $this->isValidToken = false;
        }
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::broker()->reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));

            return redirect(route('app.login'));
        }

        $this->addError('email', __($status));
    }
};
