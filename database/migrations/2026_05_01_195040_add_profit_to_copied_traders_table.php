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
        Schema::table('copied_traders', function (Blueprint $table) {
            $table->decimal('profit', 18, 8)->default(0)->after('trader_id');
            $table->dropColumn('investment_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('copied_traders', function (Blueprint $table) {
            $table->decimal('investment_amount', 18, 8)->after('trader_id');
            $table->dropColumn('profit');
        });
    }
};
