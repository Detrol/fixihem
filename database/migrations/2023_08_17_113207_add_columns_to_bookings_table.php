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
            Schema::table('bookings', function (Blueprint $table) {
                $table->integer('status')->default(0);
                $table->integer('discounted')->default(0);
                $table->integer('invoiced')->default(0);
                $table->date('date_paid')->nullable();
                $table->integer('per_hour')->default(0);
                $table->string('discount_code')->nullable();
                $table->text('comment')->nullable();
                $table->integer('expected_time')->default(0);
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->integer('email_reminder')->default(0);
                $table->integer('sms_reminder')->default(0);
                $table->decimal('addition_kr', 8, 2)->default(0);
                $table->decimal('addition_percent', 8, 2)->default(0);
                $table->decimal('deduction_kr', 8, 2)->default(0);
                $table->decimal('deduction_percent', 8, 2)->default(0);
                $table->text('addition_comment')->nullable();
                $table->text('deduction_comment')->nullable();
                $table->text('admin_comment')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
