<?php

namespace App\Http\Resources\Forms;

use App\Http\Resources\ProductionTypesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FormsManufacturingResource extends JsonResource
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
            'production_type_id' => $this->production_type_id,
            'production_type' => $this->productionType ? (new ProductionTypesResource($this->productionType))->only(['id', 'name']) : NULL ,
            'factory_starting_time' => $this->factory_starting_time,
            'factory_ending_time' => $this->factory_ending_time,
        ];
    }
}
