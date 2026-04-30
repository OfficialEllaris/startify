<?php

use App\Enums\UserRole;
use App\Models\Stake;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard'), Title('Manage Clients')] class extends Component {
    use WithPagination;

    public string $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    // Manage User State
    public ?int $managingUserId = null;
    public string $manageTab = 'wallet'; // wallet, staking
    public ?int $contactUserId = null;

    public function showContact(int $userId)
    {
        $this->contactUserId = $userId;
    }

    public function closeContactModal()
    {
        $this->contactUserId = null;
    }

    // Wallet Adjustment State
    public string $selectedAssetId = 'bitcoin';

    public string $adjustmentAmount = ''; // This will now represent USD

    public string $adjustmentType = 'credit'; // credit, debit

    // Staking Adjustment State
    public ?int $selectedStakeId = null;

    public string $rewardAmount = '';

    public string $rewardAdjustmentMode = 'credit'; // credit, debit

    public function mount()
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }
    }

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function deleteUser(int $userId): void
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }

        $user = User::findOrFail($userId);
        if ($user->isManager()) {
            $this->dispatch('notify', message: 'Cannot delete a manager account.', type: 'error');

            return;
        }

        $user->delete();
        $this->dispatch('notify', message: 'User deleted successfully.', type: 'success');
    }

    public function manageUser(int $userId): void
    {
        $this->managingUserId = $userId;
        $this->manageTab = 'wallet';
        $this->resetAdjustmentFields();
    }

    public function closeModal(): void
    {
        $this->managingUserId = null;
        $this->resetAdjustmentFields();
    }

    private function resetAdjustmentFields(): void
    {
        $this->adjustmentAmount = '';
        $this->adjustmentType = 'credit';
        $this->rewardAmount = '';
        $this->rewardAdjustmentMode = 'credit';
        $this->selectedStakeId = null;
    }

    public function applyWalletAdjustment(): void
    {
        $this->validate([
            'adjustmentAmount' => 'required|numeric|min:0.01',
            'selectedAssetId' => 'required|string',
            'adjustmentType' => 'required|in:credit,debit',
        ]);

        $user = User::findOrFail($this->managingUserId);
        $wallet = $user->wallet;

        if (!$wallet) {
            $wallet = $user->wallet()->create(['balances' => []]);
        }

        $prices = $this->prices;
        $usdPrice = (float) ($prices[$this->selectedAssetId] ?? 0);

        if ($usdPrice <= 0) {
            $this->addError('adjustmentAmount', 'Unable to fetch current market price for this asset.');

            return;
        }

        $cryptoAmount = (float) $this->adjustmentAmount / $usdPrice;
        $balances = $wallet->balances ?? [];
        $currentBalance = (float) ($balances[$this->selectedAssetId] ?? 0);

        if ($this->adjustmentType === 'debit') {
            if ($currentBalance < $cryptoAmount) {
                $this->addError('adjustmentAmount', 'Insufficient balance for debit.');

                return;
            }
            $newBalance = $currentBalance - $cryptoAmount;
        } else {
            $newBalance = $currentBalance + $cryptoAmount;
        }

        $balances[$this->selectedAssetId] = (string) $newBalance;
        $wallet->update(['balances' => $balances]);

        // Clear user portfolio cache
        Cache::forget('assets_v2_' . $user->id);

        // Log transaction

        $user->transactions()->create([
            'asset_id' => $this->selectedAssetId,
            'amount' => $cryptoAmount,
            'network_fee' => '0',
            'recipient_address' => $this->adjustmentType === 'credit' ? 'System Credit' : 'System Debit',
            'type' => $this->adjustmentType === 'credit' ? 'receive' : 'send',
            'status' => 'completed',
            'hash' => 'ADMIN-' . strtoupper(bin2hex(random_bytes(8))),
        ]);



        $this->resetAdjustmentFields();
        $this->dispatch('notify', message: 'Wallet adjusted successfully!');
    }

    public function deleteTransaction(int $transactionId): void
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }

        $transaction = \App\Models\Transaction::findOrFail($transactionId);
        $user = $transaction->user;
        $wallet = $user->wallet;

        if (!$wallet) {
            $this->dispatch('notify', message: 'User wallet not found.', type: 'error');

            return;
        }

        $balances = $wallet->balances ?? [];
        $currentBalance = (float) ($balances[$transaction->asset_id] ?? 0);
        $amount = (float) $transaction->amount;

        // Revert balance based on transaction type
        if ($transaction->type === 'receive') {
            // Deleting a credit -> subtract from balance
            if ($currentBalance < $amount) {
                $this->dispatch('notify', message: 'Insufficient balance to revert this credit.', type: 'error');

                return;
            }
            $newBalance = $currentBalance - $amount;
        } else {
            // Deleting a debit -> add back to balance
            $newBalance = $currentBalance + $amount;
        }

        $balances[$transaction->asset_id] = (string) $newBalance;
        $wallet->update(['balances' => $balances]);

        // Clear user portfolio cache
        Cache::forget('assets_v2_' . $user->id);


        $transaction->delete();
        $this->dispatch('notify', message: 'Transaction deleted and balance reconciled!');
    }


    public function addStakeReward(): void
    {
        $this->validate([
            'selectedStakeId' => 'required|exists:stakes,id',
            'rewardAmount' => 'required|numeric|min:0.01',
        ]);

        $stake = Stake::findOrFail($this->selectedStakeId);
        $assetId = $stake->asset_id;
        $price = (float) ($this->prices[$assetId] ?? 0);

        if ($price <= 0) {
            $this->addError('rewardAmount', 'Unable to fetch current market price for yield calculation.');

            return;
        }

        $cryptoAmount = (float) $this->rewardAmount / $price;

        if ($this->rewardAdjustmentMode === 'debit') {
            if ($cryptoAmount > (float) $stake->earned_rewards) {
                $this->addError('rewardAmount', 'Insufficient Rewards Accrued.');

                return;
            }
            $stake->decrement('earned_rewards', $cryptoAmount);
        } else {
            $stake->increment('earned_rewards', $cryptoAmount);
        }

        $stake->update(['last_reward_at' => now()]);

        $this->resetAdjustmentFields();
        $this->dispatch('notify', message: 'Stake balance adjusted successfully!');
    }

    #[Computed]
    public function validators(): array
    {
        return [
            [
                'id' => 'validator-1',
                'name' => config('app.name') . ' Core',
                'commission' => '5%',
                'uptime' => '99.99%',
                'status' => 'active',
                'reliability' => 'Optimal'
            ],
            [
                'id' => 'validator-2',
                'name' => 'P2P Staking',
                'commission' => '7%',
                'uptime' => '99.95%',
                'status' => 'active',
                'reliability' => 'High'
            ],
            [
                'id' => 'validator-3',
                'name' => 'Everstake',
                'commission' => '10%',
                'uptime' => '99.98%',
                'status' => 'active',
                'reliability' => 'High'
            ],
            [
                'id' => 'validator-4',
                'name' => 'Binance Staking',
                'commission' => '15%',
                'uptime' => '100%',
                'status' => 'active',
                'reliability' => 'Maximum'
            ],
        ];
    }

    #[Computed]
    public function clients()
    {
        $query = User::where('role', UserRole::Client)
            ->withCount('businesses')
            ->with(['wallet']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);
    }

    #[Computed]
    public function managingUser(): ?User
    {
        return $this->managingUserId ? User::with(['wallet', 'stakes'])->find($this->managingUserId) : null;
    }

    #[Computed]
    public function contactUser(): ?User
    {
        return $this->contactUserId ? User::find($this->contactUserId) : null;
    }

    #[Computed]
    public function transactions()
    {
        if (!$this->managingUserId) {
            return collect();
        }

        return \App\Models\Transaction::where('user_id', $this->managingUserId)
            ->latest()
            ->paginate(10, pageName: 'historyPage');
    }



    #[Computed]
    public function availableAssets(): array
    {
        $jsonPath = database_path('data/assets.json');
        if (!file_exists($jsonPath)) {
            return ['bitcoin', 'ethereum', 'tether', 'solana', 'binancecoin'];
        }

        return json_decode(file_get_contents($jsonPath), true);
    }

    #[Computed]
    public function assetNames(): array
    {
        return [
            'bitcoin' => 'Bitcoin',
            'ethereum' => 'Ethereum',
            'tether' => 'Tether',
            'binancecoin' => 'BNB',
            'solana' => 'Solana',
            'ripple' => 'XRP',
            'usd-coin' => 'USDC',
            'staked-ether' => 'Lido Staked Ether',
            'dogecoin' => 'Dogecoin',
            'cardano' => 'Cardano',
            'shiba-inu' => 'Shiba Inu',
            'avalanche-2' => 'Avalanche',
            'tron' => 'TRON',
            'polkadot' => 'Polkadot',
            'chainlink' => 'Chainlink',
            'stellar' => 'Stellar',
            'xdce-crowd-sale' => 'XDC Network',
        ];
    }

    #[Computed]
    public function prices(): array
    {
        return Cache::remember('market_prices', 300, function () {
            $apiKey = env('COINGECKO_API_KEY');
            $ids = implode(',', $this->availableAssets);

            $response = Http::withHeaders([
                'x-cg-demo-api-key' => $apiKey,
            ])->get('https://api.coingecko.com/api/v3/simple/price', [
                        'ids' => $ids,
                        'vs_currencies' => 'usd',
                    ]);

            if ($response->successful()) {
                return collect($response->json())->mapWithKeys(function ($data, $id) {
                    return [$id => $data['usd']];
                })->toArray();
            }

            return [];
        });
    }

    #[Computed]
    public function cryptoEquivalent(): float
    {
        if (!is_numeric($this->adjustmentAmount) || (float) $this->adjustmentAmount <= 0) {
            return 0;
        }

        $usdPrice = (float) ($this->prices[$this->selectedAssetId] ?? 0);

        return $usdPrice > 0 ? (float) $this->adjustmentAmount / $usdPrice : 0;
    }

    #[Computed]
    public function stakingCryptoEquivalent(): float
    {
        if (!$this->selectedStakeId || !$this->rewardAmount || !is_numeric($this->rewardAmount)) {
            return 0;
        }

        $stake = Stake::find($this->selectedStakeId);
        if (!$stake) {
            return 0;
        }

        $price = (float) ($this->prices[$stake->asset_id] ?? 0);

        return $price > 0 ? (float) $this->rewardAmount / $price : 0;
    }

    #[Computed]
    public function assetSymbols(): array
    {
        return [
            'bitcoin' => 'BTC',
            'ethereum' => 'ETH',
            'tether' => 'USDT',
            'binancecoin' => 'BNB',
            'solana' => 'SOL',
            'ripple' => 'XRP',
            'usd-coin' => 'USDC',
            'staked-ether' => 'stETH',
            'dogecoin' => 'DOGE',
            'cardano' => 'ADA',
            'shiba-inu' => 'SHIB',
            'avalanche-2' => 'AVAX',
            'tron' => 'TRX',
            'polkadot' => 'DOT',
            'chainlink' => 'LINK',
            'stellar' => 'XLM',
            'xdce-crowd-sale' => 'XDC',
        ];
    }
};



