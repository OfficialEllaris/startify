<?php

use App\Services\RegistrationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts::dashboard')] class extends Component {
    #[Url]
    public string $token = '';

    public string $status = 'verifying';

    public function mount(RegistrationService $service)
    {
        if (empty($this->token)) {
            $this->status = 'invalid';

            return;
        }

        $user = $service->verifyAndRegister($this->token);

        if ($user) {
            Auth::login($user);
            $this->status = 'success';
            $this->redirect(route('app.dashboard'));
        } else {
            $this->status = 'invalid';
        }
    }
};
