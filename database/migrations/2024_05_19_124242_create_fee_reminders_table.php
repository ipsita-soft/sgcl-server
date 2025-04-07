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
        Schema::create('fee_reminders', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->decimal('amount', 20, 2)->nullable();
            $table->date('date')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->foreignId('sender')->nullable()->index()->constrained('users');
            $table->foreignId('send_to')->nullable()->index()->constrained('users');
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_reminders');
    }
};
