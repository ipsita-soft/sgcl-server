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
        Schema::create('expected_gas_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->string('year')->nullable();
            $table->string('demand')->nullable();
            $table->string('cubic_meter')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expected_gas_needs');
    }
};
