<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Test Client',
            'email' => 'client@startify.test',
            'password' => bcrypt('password'),
        ]);

        $jsonPath = database_path('data/assets.json');
        $assetList = json_decode(file_get_contents($jsonPath), true);
        
        $balances = [];
        foreach ($assetList as $assetId) {
            $balances[$assetId] = match($assetId) {
                'bitcoin' => '1.25',
                'ethereum' => '15.4',
                'solana' => '120.0',
                'tether' => '5420.0',
                'binancecoin' => '12.5',
                default => '0.00'
            };
        }

        $user->wallet()->create([
            'balances' => $balances,
        ]);

        // Create some sample transactions
        $user->transactions()->createMany([
            [
                'asset_id' => 'bitcoin',
                'amount' => '0.05',
                'network_fee' => '0.0002',
                'recipient_address' => '0x71C7656EC7ab88b098defB751B7401B5f6d8976F',
                'type' => 'send',
                'status' => 'completed',
                'hash' => '0x' . bin2hex(random_bytes(20)),
                'created_at' => now()->subDays(2),
            ],
            [
                'asset_id' => 'ethereum',
                'amount' => '2.5',
                'network_fee' => '0.005',
                'recipient_address' => '0x1234567890abcdef1234567890abcdef12345678',
                'type' => 'send',
                'status' => 'completed',
                'hash' => '0x' . bin2hex(random_bytes(20)),
                'created_at' => now()->subHours(5),
            ],
            [
                'asset_id' => 'solana',
                'amount' => '10.0',
                'network_fee' => '0.000005',
                'recipient_address' => '5o98d...f6d89',
                'type' => 'receive',
                'status' => 'completed',
                'hash' => '0x' . bin2hex(random_bytes(20)),
                'created_at' => now()->subMinutes(30),
            ],
        ]);
    }
}
