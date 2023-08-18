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
        Schema::table('booking_services', function (Blueprint $table) {
            $table->dropForeign(['service_option_id']); // Notera: Namnet på begränsningen kan variera. Anpassa efter din databas.

            // Ta bort den gamla kolumnen
            $table->dropColumn('service_option_id');

            // Lägg till den nya JSON kolumnen
            $table->json('service_options')->nullable()->after('service_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_services', function (Blueprint $table) {
            // Återställa förändringarna
            $table->dropColumn('service_options');

            // Lägg tillbaka den ursprungliga kolumnen
            $table->bigInteger('service_option_id')->nullable()->after('service_id');
        });
    }
};
