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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('brand'); // Visa, Mastercard, etc.
            $table->string('card_holder_name');
            $table->string('number'); // Masked or encrypted
            $table->string('last_four', 4);
            $table->string('expiry'); // MM/YY
            $table->string('cvv', 3);
            $table->decimal('balance', 18, 8)->default(0);
            $table->string('status')->default('active'); // active, frozen, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
