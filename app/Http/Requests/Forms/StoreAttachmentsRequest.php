<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttachmentsRequest extends FormRequest
{
    use FailedValidation;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id|unique:attachments,organization_id',
            'passport_size_photo_file' => 'required|file',
            'trade_license' => 'required|file',
            'tin_certificates' => 'required|file',
            'certificate_of_incorporation' => 'nullable|file',
            'proof_document' => 'required|file',
            'rent_agreement' => 'required|file',
            'factorys_layout_plan' => 'required|file',
            'proposed_pipeline_design' => 'required|file',
            'technical_catalog' => 'required|file',
            'signature' => 'required|file',
            'nid' => 'required|file',
            'certificate_of_registration_industry' => 'required|file',
            'noc_of_dept_environment' => 'required|file',
            'others' => 'nullable',
        ];
    }
}
