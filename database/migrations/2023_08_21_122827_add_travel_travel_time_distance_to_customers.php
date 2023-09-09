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
            $table->decimal('to_customer_distance', 8, 2); // total 8 digits, 2 after the decimal
            $table->integer('to_customer_time');
            $table->decimal('to_customer_price', 8, 2);

            $table->decimal('to_location_distance', 8, 2)->nullable();
            $table->integer('to_location_time')->nullable();
            $table->decimal('to_location_price', 8, 2)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
