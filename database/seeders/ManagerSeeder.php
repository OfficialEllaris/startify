<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\PlaceholderDepositAddress;
use Illuminate\Database\Seeder;

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
            $addresses[$assetId] = PlaceholderDepositAddress::generate($assetId);
        }

        $manager->wallet()->create([
            'addresses' => $addresses,
        ]);
    }
}
