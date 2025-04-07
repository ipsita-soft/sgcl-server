<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreAuthorityContactDetailsRequest extends FormRequest
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
            'authorityContactDetails.*.organization_id' => 'required|exists:organizations,id',
            'authorityContactDetails.*.name' => 'required|string',
            'authorityContactDetails.*.designation' => 'required|string',
            'authorityContactDetails.*.national_id' => 'required|string',
            'authorityContactDetails.*.mobile' => 'required|string',
            'authorityContactDetails.*.email' => 'required|string|email',
        ];
    }
}
