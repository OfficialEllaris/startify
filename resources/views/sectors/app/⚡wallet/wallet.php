<?php

use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Layout('layouts.wallet')] class extends Component {
    #[Url(as: 'v')]
    public string $view = 'overview';

    public string $selectedAssetId = 'bitcoin';

    public function mount()
    {
        // Ensure user has a wallet for this demo
        if (auth()->check() && !auth()->user()->wallet) {
            auth()->user()->wallet()->create([
                'balances' => [
                    'bitcoin' => '0.4523',
                    'ethereum' => '4.234',
                    'tether' => '5400.00',
                    'binancecoin' => '12.5',
                    'solana' => '120.5',
                    'ripple' => '1500.0',
                    'usd-coin' => '2300.0',
                    'staked-ether' => '1.5',
                    'dogecoin' => '45000',
                    'cardano' => '8500',
                    'shiba-inu' => '1500000',
                    'avalanche-2' => '45.2',
                    'tron' => '12000',
                    'polkadot' => '320',
                    'chainlink' => '150'
                ]
            ]);
        }
    }

    #[Computed]
    public function assets()
    {
        return Cache::remember('coingecko_assets_v2', 3600, function () {
            $apiKey = env('COINGECKO_API_KEY');
            
            $response = Http::withHeaders([
                'x-cg-demo-api-key' => $apiKey
            ])->get('https://api.coingecko.com/api/v3/coins/markets', [
                'vs_currency' => 'usd',
                'order' => 'market_cap_desc',
                'per_page' => 15,
                'page' => 1,
                'sparkline' => false,
            ]);

            $data = $response->successful() ? $response->json() : [];
            
            $balances = auth()->check() && auth()->user()->wallet 
                ? auth()->user()->wallet->balances 
                : [];

            return collect($data)->map(function ($coin) use ($balances) {
                $change = $coin['price_change_percentage_24h'] ?? 0;
                $changeStr = $change > 0 ? '+' . number_format($change, 2) . '%' : number_format($change, 2) . '%';
                
                // Extract dominant color from image
                $color = $this->getDominantColor($coin['image'], $coin['id']);
                
                $balance = $balances[$coin['id']] ?? number_format(rand(10, 1000) / 7, 2);

                return [
                    'id' => $coin['id'],
                    'name' => $coin['name'],
                    'symbol' => strtoupper($coin['symbol']),
                    'image' => $coin['image'] ?? '',
                    'color' => $color,
                    'balance' => $balance,
                    'usd' => number_format($coin['current_price'], 2),
                    'usd_total' => number_format($coin['current_price'] * (float)str_replace(',', '', $balance), 2),
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

    public function selectAsset(string $id)
    {
        $this->selectedAssetId = $id;
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