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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organization_id')->nullable()->index()->constrained('organizations');
            $table->string('mouza_name')->nullable();
            $table->string('daag_no')->nullable();
            $table->string('khotiyan_no')->nullable();
            $table->string('total_land_area')->nullable();
            $table->foreignId('land_ownership_id')->nullable()->index()->constrained('land_ownerships');
            $table->integer('land_width_feet')->nullable();
            $table->integer('land_length_feet')->nullable();
            $table->string('owner_name_ifRented')->nullable();
            $table->string('owner_address_ifRented')->nullable();
            $table->string('lease_provider_organization_name_Ifleased')->nullable();
            $table->string('lease_provider_organization_address_if_leased')->nullable();
            $table->tinyInteger('any_other_customer_used_gas')->nullable()->comment('1=yes , 2 = no');; //  1 yes , 2 no
            $table->integer('customer_code_no')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->tinyInteger('connection_status')->nullable()->comment('1 = continuing , 2 Temporary Disconnected , 3 Permanently Disconnected'); //1 contnuing , 2 Temporary Discounnected , 3 Parmanently Discounnected
            $table->tinyInteger('clearance_of_gas_bill')->nullable()->comment('1=yes , 2 = no'); //  1 yes , 2 no
            $table->tinyInteger('is_organization_owner')->nullable()->comment('1=yes , 2 = no'); //  1 yes , 2 no
            $table->string('owner_partner_code')->nullable();
            $table->string('owner_partner_name')->nullable();
            $table->string('owner_partner_status')->nullable();

            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
