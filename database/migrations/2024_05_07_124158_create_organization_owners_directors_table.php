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
        Schema::create('organization_owners_directors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->string('father_or_husband_name')->nullable();
            $table->string('present_address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('designation')->nullable();
            $table->string('relation_with_other_org')->nullable();
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_owners_directors');
    }
};
