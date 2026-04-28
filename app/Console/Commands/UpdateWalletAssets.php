<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\Wallet;
use App\Support\PlaceholderDepositAddress;
use Illuminate\Console\Command;
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
    protected $description = 'Sync balances and manager deposit addresses from database/data/assets.json for every wallet';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $jsonPath = database_path('data/assets.json');

        if (! File::exists($jsonPath)) {
            $this->error("Assets JSON not found at: {$jsonPath}");

            return self::FAILURE;
        }

        $assetList = json_decode(File::get($jsonPath), true);

        if (! is_array($assetList)) {
            $this->error('Invalid JSON format in assets.json');

            return self::FAILURE;
        }

        $wallets = Wallet::with('user')->get();
        $totalUpdated = 0;

        foreach ($wallets as $wallet) {
            $balances = $wallet->balances ?? [];
            $addresses = $wallet->addresses ?? [];
            $updated = false;

            $isManager = $wallet->user && $wallet->user->role === UserRole::Manager;

            foreach ($assetList as $assetId) {
                if (! isset($balances[$assetId])) {
                    $balances[$assetId] = '0.00';
                    $updated = true;
                }

                if ($isManager && ! isset($addresses[$assetId])) {
                    $addresses[$assetId] = PlaceholderDepositAddress::generate($assetId);
                    $updated = true;
                }
            }

            if ($updated) {
                $wallet->balances = $balances;
                if ($isManager) {
                    $wallet->addresses = $addresses;
                }
                $wallet->save();
                $totalUpdated++;
            }
        }

        $this->info("Successfully updated {$totalUpdated} wallet(s); asset list driven by assets.json (no migration needed for new coins).");

        return self::SUCCESS;
    }
}
