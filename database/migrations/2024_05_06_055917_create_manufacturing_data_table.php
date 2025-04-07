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
        Schema::create('manufacturing_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->foreignId('production_type_id')->nullable()->index()->constrained('production_types');
            $table->time('factory_starting_time')->nullable();
            $table->time('factory_ending_time')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_data');
    }
};
