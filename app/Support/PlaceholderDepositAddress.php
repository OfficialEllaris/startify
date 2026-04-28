<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Generates deterministic-style placeholder crypto addresses per asset id (manager deposit JSON).
 */
final class PlaceholderDepositAddress
{
    public static function generate(string $assetId): string
    {
        return match ($assetId) {
            'bitcoin' => 'bc1'.Str::random(39),
            'ethereum', 'tether', 'usd-coin', 'staked-ether', 'shiba-inu' => '0x'.Str::random(40),
            'solana' => Str::random(44),
            'ripple' => 'r'.Str::random(33),
            'binancecoin' => 'bnb'.Str::random(39),
            'cardano' => 'addr1'.Str::random(98),
            'polkadot' => '1'.Str::random(46),
            'tron' => 'T'.Str::random(33),
            'dogecoin' => 'D'.Str::random(33),
            'avalanche-2' => '0x'.Str::random(40),
            default => '0x'.Str::random(40),
        };
    }
}
