<?php

namespace App\Http\Resources;

use App\Http\Resources\Forms\ApplianceAndBurnerInfoResource;
use App\Http\Resources\Forms\ApplianceBurnerResource;
use App\Http\Resources\Forms\AttachmentsResource;
use App\Http\Resources\Forms\AuthorityContactDetailsResource;
use App\Http\Resources\Forms\ExpectedGasNeedResource;
use App\Http\Resources\Forms\FormsFinancialInfoResource;
use App\Http\Resources\Forms\FormsManufacturingResource;
use App\Http\Resources\Forms\IngredientsInfoForProductionResource;
use App\Http\Resources\Forms\OrganizationOwnersDirectorResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use phpDocumentor\Reflection\Types\Null_;

class ApplicationFormViewResource extends JsonResource
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
            'organization_category' => $this->organizationCategory ? (new OrganizationCategoryResource($this->organizationCategory))->only(['id', 'name']) : NULL,
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
            'ownershipType' => $this->ownershipType ? (new OrganizationOwnershipTypes($this->ownershipType))->only(['id', 'name']) : NULL,
            'industryType' => $this->industryType ? (new IndustryTypesResource($this->industryType))->only(['id', 'name']) : NULL,

            'trade_license_no' => $this->trade_license_no,
            'license_expiry_date' => $this->license_expiry_date,
            'applicants_name' => $this->applicants_name,
            'applicants_designation' => $this->applicants_designation,
            'partner_customer_code_no' => $this->partner_customer_code_no,
            'other_organization_name' => $this->other_organization_name,
            'other_organization_status' => $this->other_organization_status,
            'user' => new UserResource($this->whenLoaded('user')),
            'applianceBurnerDetails' => ApplianceBurnerResource::collection($this->whenLoaded('applianceBurnerDetails')),
            'applianceBurnerInfo' => new ApplianceAndBurnerInfoResource($this->whenLoaded('applianceBurnerInfo')),
            'attachment' => new AttachmentsResource($this->whenLoaded('attachment')),
            'authorityContactDetails' => AuthorityContactDetailsResource::collection($this->whenLoaded('authorityContactDetails')),
            'expectedGasNeed' => ExpectedGasNeedResource::collection($this->whenLoaded('expectedGasNeed')),
            'financialInformation' => new FormsFinancialInfoResource($this->whenLoaded('financialInformation')),
            'ingredientsInfoForProduction' => IngredientsInfoForProductionResource::collection($this->whenLoaded('ingredientsInfoForProduction')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'manufacturingData' => new FormsManufacturingResource($this->whenLoaded('manufacturingData')),
            'organizationOwnersDirector' => OrganizationOwnersDirectorResource::collection($this->whenLoaded('organizationOwnersDirector')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
