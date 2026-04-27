<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Wallet;
use Illuminate\Support\Facades\File;

class UpdateWalletAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:update-assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing assets from the JSON config to all user wallets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = database_path('data/assets.json');
        
        if (!File::exists($jsonPath)) {
            $this->error("Assets JSON not found at: {$jsonPath}");
            return;
        }

        $assetList = json_decode(File::get($jsonPath), true);
        
        if (!is_array($assetList)) {
            $this->error("Invalid JSON format in assets.json");
            return;
        }

        $wallets = Wallet::all();
        $totalUpdated = 0;

        foreach ($wallets as $wallet) {
            $balances = $wallet->balances ?? [];
            $addresses = $wallet->addresses ?? [];
            $updated = false;
            
            $isAdmin = $wallet->user && $wallet->user->role === \App\Enums\UserRole::Manager;

            foreach ($assetList as $assetId) {
                if (!isset($balances[$assetId])) {
                    $balances[$assetId] = "0.00";
                    $updated = true;
                }
                
                if ($isAdmin && !isset($addresses[$assetId])) {
                    // Generate a placeholder address ONLY for the admin
                    $addresses[$assetId] = '0x' . bin2hex(random_bytes(20));
                    $updated = true;
                }
            }

            if ($updated) {
                $wallet->balances = $balances;
                if ($isAdmin) {
                    $wallet->addresses = $addresses;
                }
                $wallet->save();
                $totalUpdated++;
            }
        }

        $this->info("Successfully updated {$totalUpdated} wallets with new assets.");
    }
}
