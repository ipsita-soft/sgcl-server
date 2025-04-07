<?php

namespace App\Http\Resources\Forms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplianceAndBurnerInfoResource extends JsonResource
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
            'gas_usage_hours' => $this->gas_usage_hours,
            'gas_usage_unit' => $this->gas_usage_unit,
            'expected_gas_parssure' => $this->expected_gas_parssure,
        ];
    }
}
