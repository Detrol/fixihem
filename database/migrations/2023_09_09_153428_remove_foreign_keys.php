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
            $table->dropForeign('bookings_customer_id_foreign');
        });

        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropForeign('booking_services_booking_id_foreign');
            $table->dropForeign('booking_services_service_id_foreign');
        });

        /*Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_order_id_foreign');
        });*/

        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign('services_category_id_foreign');
        });

        Schema::table('service_options', function (Blueprint $table) {
            $table->dropForeign('service_options_service_id_foreign');
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
