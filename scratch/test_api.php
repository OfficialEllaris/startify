<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

$jsonPath = database_path('data/assets.json');
$allowedAssets = json_decode(File::get($jsonPath), true);
$ids = implode(',', $allowedAssets);
$apiKey = env('COINGECKO_API_KEY');

echo "Fetching IDs: $ids\n";

$response = Http::withHeaders([
    'x-cg-demo-api-key' => $apiKey
])->get('https://api.coingecko.com/api/v3/coins/markets', [
    'vs_currency' => 'usd',
    'ids' => $ids,
    'order' => 'market_cap_desc',
    'per_page' => 250,
    'page' => 1,
    'sparkline' => false,
]);

if ($response->successful()) {
    $data = $response->json();
    echo "Success! Count: " . count($data) . "\n";
    foreach ($data as $coin) {
        echo " - " . $coin['id'] . " (" . $coin['name'] . ")\n";
    }
} else {
    echo "Error: " . $response->status() . "\n";
    echo $response->body() . "\n";
}
