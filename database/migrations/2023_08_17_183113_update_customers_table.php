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
        Schema::table('customers', function (Blueprint $table) {
            // Ta bort existerande kolumner
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_email');
            $table->dropColumn('customer_phone');

            // Lägg till nya kolumner
            $table->string('first_name');
            $table->string('last_name');
            $table->string('personal_number');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('door_code')->nullable();  // eftersom detta fält kanske inte alltid fylls i
            $table->string('billing_method');
            $table->integer('travel_time')->nullable();
            $table->decimal('distance', 8, 2)->nullable();  // precision på 8 och skala på 2, anpassa efter behov
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
