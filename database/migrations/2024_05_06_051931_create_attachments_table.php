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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->string('passport_size_photo_file')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('tin_certificates')->nullable();
            $table->string('certificate_of_incorporation')->nullable();
            $table->string('proof_document')->nullable();
            $table->string('rent_agreement')->nullable();
            $table->string('factorys_layout_plan')->nullable();
            $table->string('proposed_pipeline_design')->nullable();
            $table->string('technical_catalog')->nullable();
            $table->string('signature')->nullable();
            $table->string('nid')->nullable();
            $table->string('certificate_of_registration_industry')->nullable();
            $table->string('noc_of_dept_environment')->nullable();
            $table->string('others')->nullable();

            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
