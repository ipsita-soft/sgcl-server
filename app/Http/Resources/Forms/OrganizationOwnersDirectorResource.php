<?php

namespace App\Http\Resources\Forms;

use App\Http\Resources\OrganizationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationOwnersDirectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'organization_id' => $this->organization_id,
            'father_or_husband_name' => $this->father_or_husband_name,
            'present_address' => $this->present_address,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'designation' => $this->designation,
            'relation_with_other_org' => $this->relation_with_other_org,
        ];
    }
}
