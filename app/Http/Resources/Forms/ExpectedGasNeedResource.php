<?php

namespace App\Http\Resources\Forms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpectedGasNeedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'organization_id' => $this->organization_id,
            'year' => $this->year,
            'demand' => $this->demand,
            'cubic_meter' => $this->cubic_meter,
        ];
    }
}
