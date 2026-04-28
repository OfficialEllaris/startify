<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $showModal = false;

    public array $wallet_addresses = [];

    public function mount()
    {
        $this->name = auth()->user()->name;
        $this->email = auth()->user()->email;

        if (auth()->user()->isManager()) {
            $addresses = auth()->user()->wallet?->addresses ?? [];

            // Ensure all assets from assets.json are present and ONLY those are present
            $jsonPath = database_path('data/assets.json');
            if (file_exists($jsonPath)) {
                $allowedAssets = json_decode(file_get_contents($jsonPath), true);
                $this->wallet_addresses = [];

                foreach ($allowedAssets as $assetId) {
                    $this->wallet_addresses[$assetId] = $addresses[$assetId] ?? '';
                }
            }
        }
    }

    #[On('open-profile-modal')]
    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('current_password', 'password', 'password_confirmation');
        $this->resetValidation();
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->dispatch('profile-updated');
        session()->flash('status', 'profile-updated');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');
        session()->flash('status', 'password-updated');
    }

    public function updateWalletAddresses()
    {
        if (! auth()->user()->isManager()) {
            return;
        }

        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create([
            'balances' => [],
            'addresses' => [],
        ]);

        $wallet->update([
            'addresses' => $this->wallet_addresses,
        ]);

        session()->flash('status', 'wallet-updated');
    }
};
