<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'mouza_name' => $this->mouza_name,
            'daag_no' => $this->daag_no,
            'khotiyan_no' => $this->khotiyan_no,
            'total_land_area' => $this->total_land_area,
            'land_ownership_id' => $this->land_ownership_id,
            'land_ownership' => $this->landOwnership ? (new LandOwnershipResource($this->landOwnership))->only(['id', 'name']) : NULL,
            'land_width_feet' => $this->land_width_feet,
            'land_length_feet' => $this->land_length_feet,
            'owner_name_ifRented' => $this->owner_name_ifRented,
            'owner_address_ifRented' => $this->owner_address_ifRented,
            'lease_provider_organization_name_Ifleased' => $this->lease_provider_organization_name_Ifleased,
            'lease_provider_organization_address_if_leased' => $this->lease_provider_organization_address_if_leased,
            'any_other_customer_used_gas' => $this->any_other_customer_used_gas,
            'customer_code_no' => $this->customer_code_no,
            'organization_name' => $this->organization_name,
            'customer_name' => $this->customer_name,
            'connection_status' => $this->connection_status,
            'clearance_of_gas_bill' => $this->clearance_of_gas_bill,
            'is_organization_owner' => $this->is_organization_owner,
            'owner_partner_code' => $this->owner_partner_code,
            'owner_partner_name' => $this->owner_partner_name,
            'owner_partner_status' => $this->owner_partner_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
