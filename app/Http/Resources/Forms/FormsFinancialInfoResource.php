<?php

namespace App\Http\Resources\Forms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormsFinancialInfoResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'tax_indentification_no' => $this->tax_indentification_no,
            'vat_registration_no' => $this->vat_registration_no,
            'bank_name' => $this->bank_name,
            'bank_branch' => $this->bank_branch
        ];
    }
}
