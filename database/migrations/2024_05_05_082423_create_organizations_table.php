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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->index();
            $table->foreignId('organization_category_id')->nullable()->index()->constrained();

            $table->date('application_date')->nullable();
            $table->string('factory_name')->nullable();

            $table->string('factory_address')->nullable();
            $table->string('factory_telephone')->nullable();
            $table->string('main_office_address')->nullable();
            $table->string('main_office_telephone')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_telephone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('national_id')->nullable();
            $table->string('tax_identification_no')->nullable();
            $table->string('gis_location')->nullable();


            $table->foreignId('organization_ownership_type_id')->nullable()->index()->constrained();
            $table->foreignId('industry_type_id')->nullable()->index()->constrained();


           
            $table->string('trade_license_no')->nullable();
            $table->date('license_expiry_date')->nullable();

            $table->string('applicants_name')->nullable();
            $table->string('applicants_designation')->nullable();

            // IS ORGANIZATION OWNER, PARTNER Select Type IF YES

            $table->string('partner_customer_code_no')->nullable();
            $table->string('other_organization_name')->nullable();
            $table->string('other_organization_status')->nullable();



            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
