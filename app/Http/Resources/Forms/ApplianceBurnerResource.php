<?php

namespace App\Http\Resources\Forms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplianceBurnerResource extends JsonResource
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
            'appliance_name' => $this->appliance_name,
            'appliance_size' => $this->appliance_size,
            'appliance_production_capacity' => $this->appliance_production_capacity,
            'burner_type' => $this->burner_type,
            'burner_count' => $this->burner_count,
            'burner_capacity' => $this->burner_capacity,
            'total_load' => $this->total_load,
            'comments' => $this->comments,
        ];
    }
}
