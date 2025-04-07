<?php

namespace App\Http\Resources\Forms;

use App\Http\Resources\IndustryTypesResource;
use App\Http\Resources\OrganizationCategoryResource;
use App\Http\Resources\OrganizationOwnershipTypes;
use App\Models\IndustryType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'organization_category' => new OrganizationCategoryResource($this->organizationCategory),
            'application_date' => $this->application_date,
            'factory_name' => $this->factory_name,
            'factory_address' => $this->factory_address,
            'factory_telephone' => $this->factory_telephone,
            'main_office_address' => $this->main_office_address,
            'main_office_telephone' => $this->main_office_telephone,
            'billing_address' => $this->billing_address,
            'billing_telephone' => $this->billing_telephone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'tax_identification_no' => $this->tax_identification_no,
            'gis_location' => $this->gis_location,
            'organization_ownership_type' => new OrganizationOwnershipTypes($this->ownershipType),
            'industry_type' => new IndustryTypesResource($this->industryType),
            'trade_license_no' => $this->trade_license_no,
            'license_expiry_date' => $this->license_expiry_date,
        ];
    }
}
