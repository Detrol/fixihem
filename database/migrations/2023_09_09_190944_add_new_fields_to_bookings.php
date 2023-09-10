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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('customer_price', 8, 2)->nullable(); // eller vilken datatyp du föredrar
            $table->decimal('net_earnings', 8, 2)->nullable();  // eller vilken datatyp du föredrar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['customer_price', 'net_earnings']);
        });
    }
};
