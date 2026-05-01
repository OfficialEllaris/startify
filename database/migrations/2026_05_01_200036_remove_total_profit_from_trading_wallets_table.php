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
        Schema::table('trading_wallets', function (Blueprint $table) {
            $table->dropColumn('total_profit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trading_wallets', function (Blueprint $table) {
            $table->decimal('total_profit', 18, 8)->default(0);
        });
    }
};
