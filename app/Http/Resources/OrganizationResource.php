<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => (new UserResource($this->whenLoaded('user')))->only(['id', 'name']),
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
            'organization_ownership_type' => (new OrganizationCategoryResource($this->whenLoaded('ownershipType'))),
            'industry_type' => (new OrganizationCategoryResource($this->whenLoaded('industryType'))),
            'trade_license_no' => $this->trade_license_no,
            'license_expiry_date' => $this->license_expiry_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
