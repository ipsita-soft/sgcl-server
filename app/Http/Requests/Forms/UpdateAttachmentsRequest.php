<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttachmentsRequest extends FormRequest
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
            'organization_id' => ['required','exists:organizations,id', Rule::unique('attachments')->ignore($this->id)],
            'passport_size_photo_file' => 'nullable',
            'trade_license' => 'nullable',
            'tin_certificates' => 'nullable',
            'certificate_of_incorporation' => 'nullable',
            'proof_document' => 'nullable',
            'rent_agreement' => 'nullable',
            'factorys_layout_plan' => 'nullable',
            'proposed_pipeline_design' => 'nullable',
            'technical_catalog' => 'nullable',
            'signature' => 'nullable',
            'nid' => 'nullable',
            'certificate_of_registration_industry' => 'nullable',
            'noc_of_dept_environment' => 'nullable',
            'others' => 'nullable',
        ];
    }
}
