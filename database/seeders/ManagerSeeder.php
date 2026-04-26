<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Seed the default manager account.
     */
    public function run(): void
    {
        User::factory()->manager()->create([
            'name' => config('app.name').' Manager',
            'email' => 'manager@'.config('app.domain'),
        ]);
    }
}
