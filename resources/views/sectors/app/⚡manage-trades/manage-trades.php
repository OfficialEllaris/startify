<?php

use App\Models\CopiedTrader;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard'), Title('Manage Trades')] class extends Component {
    use WithPagination;

    public $search = '';

    public function mount()
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }
    }

    public $selectedTradeId = null;
    public $amountToAdd = 0;
    public $isAddingProfit = false;
    public $profitMode = 'credit'; // credit or debit

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openProfitModal($id)
    {
        $this->selectedTradeId = $id;
        $this->amountToAdd = "";
        $this->profitMode = 'credit';
        $this->isAddingProfit = true;
    }

    public function addProfit()
    {
        $this->validate([
            'amountToAdd' => 'required|numeric|min:0.01',
        ]);

        $adjustment = $this->profitMode === 'credit' ? $this->amountToAdd : -$this->amountToAdd;

        $trade = CopiedTrader::with('user.tradingWallet')->findOrFail($this->selectedTradeId);

        // Update trade record profit
        $trade->increment('profit', $adjustment);

        $this->isAddingProfit = false;
        $this->selectedTradeId = null;
        $this->amountToAdd = 0;
        $this->dispatch('notify', message: 'Profit adjustment processed successfully!');
    }

    public function stopCopying($id)
    {
        $trade = CopiedTrader::with('user.wallet')->findOrFail($id);

        // Auto-claim profit if any
        if ($trade->profit > 0) {
            $user = $trade->user;
            $mainWallet = $user->wallet;
            if ($mainWallet) {
                $balances = $mainWallet->balances;
                $balances['tether'] = (string) (($balances['tether'] ?? 0) + $trade->profit);
                $mainWallet->update(['balances' => $balances]);

                \Illuminate\Support\Facades\Cache::forget('assets_v2_' . $user->id);

                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $trade->profit,
                    'currency' => 'USDT',
                    'status' => 'completed',
                    'type' => 'profit_claim',
                ]);
            }
        }

        $trade->delete();
        $this->dispatch('notify', message: 'Trade stopped. Remaining profits moved to user USDT wallet.');
    }

    #[Computed]
    public function copiedTrades()
    {
        return CopiedTrader::with(['user', 'trader'])
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                })
                    ->orWhereHas('trader', function ($q) {
                        $q->where('name', 'like', "%{$this->search}%");
                    });
            })
            ->latest()
            ->paginate(15);
    }
};