<?php

namespace App\Http\Resources\Forms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentsResource extends JsonResource
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
            'passport_size_photo_file' => $this->trade_license ? asset($this->passport_size_photo_file) : '',
            'trade_license' => $this->trade_license ? asset($this->trade_license) : '',
            'tin_certificates' => $this->tin_certificates ? asset($this->tin_certificates) : '',
            'certificate_of_incorporation' => $this->certificate_of_incorporation ? asset($this->certificate_of_incorporation) : '',
            'proof_document' => $this->proof_document ? asset($this->proof_document) : '',
            'rent_agreement' => $this->rent_agreement ? asset($this->rent_agreement) : '',
            'factorys_layout_plan' => $this->factorys_layout_plan ? asset($this->factorys_layout_plan) : '',
            'proposed_pipeline_design' => $this->proposed_pipeline_design ? asset($this->proposed_pipeline_design) : '',
            'technical_catalog' => $this->technical_catalog ? asset($this->technical_catalog) : '',
            'signature' => $this->signature ? asset($this->signature) : '',
            'nid' => $this->nid ? asset($this->nid) : '',
            'certificate_of_registration_industry' => $this->certificate_of_registration_industry ? asset($this->certificate_of_registration_industry) : '',
            'others' => $this->others ? asset($this->others) : ''

        ];
    }
}
