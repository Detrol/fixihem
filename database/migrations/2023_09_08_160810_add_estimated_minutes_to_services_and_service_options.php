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
        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('estimated_minutes')->default(0);
        });

        Schema::table('service_options', function (Blueprint $table) {
            $table->unsignedInteger('estimated_minutes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services_and_service_options', function (Blueprint $table) {
            //
        });
    }
};
