<?php

namespace App\Services;

use App\Enums\BusinessStatus;
use App\Mail\MagicLinkVerification;
use App\Mail\WelcomeEmail;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationService
{
    /**
     * Cache the onboarding data and send a magic link verification email.
     */
    public function cacheRegistration(array $data): void
    {
        $token = Str::random(60);

        // Cache the data for 15 minutes
        Cache::put('registration:'.$token, $data, now()->addMinutes(15));

        // Send magic link email
        Mail::to($data['user']['email'])->send(new MagicLinkVerification($token));
    }

    /**
     * Verify the token and provision the account.
     */
    public function verifyAndRegister(string $token): ?User
    {
        $data = Cache::get('registration:'.$token);

        if (! $data) {
            return null;
        }

        return DB::transaction(function () use ($data, $token) {
            // Create the User
            $user = User::create([
                'name' => $data['user']['name'],
                'email' => $data['user']['email'],
                'phone' => $data['user']['phone'] ?? null,
                'address' => $data['user']['address'] ?? null,
                'password' => Hash::make($data['user']['password']),
                'email_verified_at' => now(),
            ]);

            // Create the Business
            $business = Business::create([
                'user_id' => $user->id,
                'state' => $data['business']['state'],
                'name' => $data['business']['name'],
                'type' => $data['business']['type'],
                'purpose' => $data['business']['purpose'],
                'use_registrar_agent' => $data['business']['use_registrar_agent'] ?? true,
                'agent_name' => $data['business']['agent_name'] ?? null,
                'agent_address' => $data['business']['agent_address'] ?? null,
                'status' => BusinessStatus::Submitted,
                'submitted_at' => now(),
            ]);

            // Provision the Wallet
            $jsonPath = database_path('data/assets.json');
            $assetList = file_exists($jsonPath) ? json_decode(file_get_contents($jsonPath), true) : ['bitcoin', 'ethereum'];
            
            $balances = [];
            foreach ($assetList as $assetId) {
                $balances[$assetId] = "0.00";
            }

            $user->wallet()->create([
                'balances' => $balances,
            ]);

            // Send welcome email
            Mail::to($user->email)->send(new WelcomeEmail($user));

            // Clear cache
            Cache::forget('registration:'.$token);

            return $user;
        });
    }
}
