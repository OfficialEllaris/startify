<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$response = Http::get('https://api.coingecko.com/api/v3/coins/list');
$coins = $response->json();

foreach ($coins as $coin) {
    if (stripos($coin['name'], 'XDC') !== false || stripos($coin['symbol'], 'XDC') !== false) {
        echo "ID: " . $coin['id'] . " | Name: " . $coin['name'] . " | Symbol: " . $coin['symbol'] . "\n";
    }
}
