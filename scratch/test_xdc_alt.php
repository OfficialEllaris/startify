<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = env('COINGECKO_API_KEY');

$response = Http::withHeaders([
    'x-cg-demo-api-key' => $apiKey
])->get('https://api.coingecko.com/api/v3/coins/markets', [
    'vs_currency' => 'usd',
    'ids' => 'xdce-crowd-sale',
]);

echo "Status: " . $response->status() . "\n";
echo "Body: " . $response->body() . "\n";
