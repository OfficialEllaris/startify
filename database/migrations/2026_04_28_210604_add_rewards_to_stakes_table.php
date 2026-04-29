<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stakes', function (Blueprint $table) {
            $table->decimal('earned_rewards', 36, 18)->default(0);
            $table->timestamp('last_reward_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stakes', function (Blueprint $table) {
            //
        });
    }
};
