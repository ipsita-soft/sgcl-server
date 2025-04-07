<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "phone" => $this->phone,
            "organization_id" => $this->organization ? $this->organization->id : NULL,
            "organization" => $this->organization,
            "role_id" => $this->role_id,
            "role" => new RoleResource($this->role),
            "email" => $this->email,
            "bida_oss_id" => $this->bida_oss_id,
            "profile_image" => $this->profile_image ? asset($this->profile_image) : '',
            "application_fee" => $this->application_fee,
            "email_verified_at" => $this->email_verified_at,
            "is_verified" => $this->is_verified,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
