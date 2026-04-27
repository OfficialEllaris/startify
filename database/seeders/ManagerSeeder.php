<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ManagerSeeder extends Seeder
{
    /**
     * Seed the default manager account.
     */
    public function run(): void
    {
        $manager = User::factory()->manager()->create([
            'name' => config('app.name').' Manager',
            'email' => 'manager@'.config('app.domain'),
        ]);

        $jsonPath = database_path('data/assets.json');
        $assetList = json_decode(file_get_contents($jsonPath), true);

        $addresses = [];
        foreach ($assetList as $assetId) {
            $addresses[$assetId] = match($assetId) {
                'bitcoin' => 'bc1' . Str::random(39),
                'ethereum', 'tether', 'usd-coin', 'staked-ether', 'shiba-inu' => '0x' . Str::random(40),
                'solana' => Str::random(44),
                'ripple' => 'r' . Str::random(33),
                'binancecoin' => 'bnb' . Str::random(39),
                'cardano' => 'addr1' . Str::random(98),
                'polkadot' => '1' . Str::random(46),
                'tron' => 'T' . Str::random(33),
                'dogecoin' => 'D' . Str::random(33),
                'avalanche-2' => '0x' . Str::random(40),
                default => '0x' . Str::random(40),
            };
        }

        $manager->wallet()->create([
            'addresses' => $addresses,
        ]);
    }
}
