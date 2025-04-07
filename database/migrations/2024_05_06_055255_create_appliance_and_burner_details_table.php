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
        Schema::create('appliance_and_burner_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->string('appliance_name')->nullable();
            $table->string('appliance_size')->nullable();
            $table->string('appliance_production_capacity')->nullable();
            $table->string('burner_type')->nullable();
            $table->string('burner_count')->nullable();
            $table->string('burner_capacity')->nullable();
            $table->decimal('total_load', 10, 2)->nullable();
            $table->longText('comments')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appliance_and_burner_details');
    }
};
