<?php

use App\Models\Trader;
use App\Models\CopiedTrader;
use App\Models\TradingWallet;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard'), Title('Copy Trading')] class extends Component {
    use WithPagination;
    public $search = '';
    public $copyingTraderId = null;
    public $isWithdrawing = false;
    public $withdrawAmount = 0;

    public function withdrawProfits()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $availableProfit = (float) $this->totalProfit;

        if ($availableProfit < $this->withdrawAmount) {
            $this->dispatch('notify', message: 'Insufficient profits to claim.', type: 'error');
            return;
        }

        // Deduct from trade profits (until amount is exhausted)
        $remainingToDeduct = (float) $this->withdrawAmount;
        foreach ($user->copiedTraders()->where('profit', '>', 0)->get() as $copy) {
            if ($remainingToDeduct <= 0) break;

            $deduction = min($copy->profit, $remainingToDeduct);
            $copy->decrement('profit', $deduction);
            $remainingToDeduct -= $deduction;
        }

        // Transfer to main wallet (USDT/Tether)
        $mainWallet = $user->wallet;
        if ($mainWallet) {
            $balances = $mainWallet->balances;
            $balances['tether'] = (string) (($balances['tether'] ?? 0) + $this->withdrawAmount);
            $mainWallet->update(['balances' => $balances]);

            // Clear wallet cache to reflect changes immediately
            \Illuminate\Support\Facades\Cache::forget('assets_v2_' . $user->id);

            // Create withdrawal record
            Withdrawal::create([
                'user_id' => $user->id,
                'amount' => $this->withdrawAmount,
                'currency' => 'USDT',
                'status' => 'completed',
                'type' => 'profit_claim',
            ]);
        }

        $this->isWithdrawing = false;
        $this->withdrawAmount = 0;
        $this->dispatch('notify', message: 'Profits claimed successfully to your USDT wallet!');
    }

    public function copyTrader($traderId)
    {
        $user = Auth::user();
        $trader = Trader::findOrFail($traderId);

        // Check if already copying
        if (CopiedTrader::where('user_id', $user->id)->where('trader_id', $traderId)->where('status', 'active')->exists()) {
            $this->dispatch('notify', message: 'You are already copying this trader.', type: 'warning');
            return;
        }

        // Create copy record (Following)
        CopiedTrader::create([
            'user_id' => $user->id,
            'trader_id' => $traderId,
            'status' => 'active',
        ]);

        $this->copyingTraderId = null;
        $this->dispatch('notify', message: "You are now copying {$trader->name}!");
    }

    public function stopCopying($copyId)
    {
        $copy = CopiedTrader::where('user_id', Auth::id())->findOrFail($copyId);

        // Auto-claim profit if any
        if ($copy->profit > 0) {
            $user = Auth::user();
            $mainWallet = $user->wallet;
            if ($mainWallet) {
                $balances = $mainWallet->balances;
                $balances['tether'] = (string) (($balances['tether'] ?? 0) + $copy->profit);
                $mainWallet->update(['balances' => $balances]);

                \Illuminate\Support\Facades\Cache::forget('assets_v2_' . $user->id);

                Withdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $copy->profit,
                    'currency' => 'USDT',
                    'status' => 'completed',
                    'type' => 'profit_claim',
                ]);
            }
        }

        $copy->delete();
        $this->dispatch('notify', message: 'Stopped copying trader. Any remaining profits have been moved to your USDT wallet.');
    }

    #[Computed]
    public function traders()
    {
        return Trader::where('is_active', true)
            ->when($this->search, function($query) {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(9);
    }

    #[Computed]
    public function activeCopies()
    {
        return CopiedTrader::with('trader')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();
    }

    #[Computed]
    public function totalProfit()
    {
        return CopiedTrader::where('user_id', Auth::id())->sum('profit');
    }

    #[Computed]
    public function wallet()
    {
        return Auth::user()->tradingWallet ?? new TradingWallet(['balance' => 0]);
    }
};