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
        Schema::create('external_client_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('clientId')->unique();
            $table->string('clientSecret')->nullable();
            $table->string('api_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_client_access_tokens');
    }
};
