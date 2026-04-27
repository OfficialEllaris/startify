<?php

use App\Models\Wallet;
use App\Models\Transaction;
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
    public bool $showSuccessModal = false;
    public ?int $lastTransactionId = null;
    public bool $isProcessing = false;
    public bool $showAllAssets = false;

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
        return Cache::remember('coingecko_assets_v2', 3600, function () {
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
                        'per_page' => count($allowedAssets), 
                        'page' => 1,
                        'sparkline' => false,
                    ]);

            $data = $response->successful() ? $response->json() : [];

            $balances = auth()->check() && auth()->user()->wallet
                ? auth()->user()->wallet->balances
                : [];

            return collect($data)
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
        });
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
        if ($usdPrice <= 0) return 0;

        // Base USD fee for different networks
        $baseFeeUsd = match($this->selectedAssetId) {
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
        if (!$admin || !$admin->wallet)
            return null;

        $address = $admin->wallet->addresses[$this->selectedAssetId] ?? null;
        if (!$address)
            return null;

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
                'xdc-network' => 'xdc:',
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
        Cache::forget('coingecko_assets_v2');
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

    public function setView(string $view)
    {
        $this->view = $view;
    }
};