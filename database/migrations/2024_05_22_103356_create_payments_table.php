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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->nullable()->constrained('users');
            $table->foreignId('fee_reminder_id')->index()->nullable()->constrained('fee_reminders');
            $table->string('invoice_no', 20)->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->decimal('total_amount', 20, 2)->nullable();
            $table->string('name_of_payee')->nullable();
            $table->string('mobile_of_payee')->nullable();
            $table->string('session_token')->index()->nullable();
            $table->string('transaction_id')->nullable();
            $table->date('transaction_date')->nullable();
            $table->string('pay_mode')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('payment_type')->default('Application Fee');
            $table->tinyInteger('status')->default(0);
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
