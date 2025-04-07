<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationOwnerDirectorRequest extends FormRequest
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
            'ownerAndDirectors' => 'required|array',
            'ownerAndDirectors.*.name' => 'required|string|max:255',
            'ownerAndDirectors.*.father_or_husband_name' => 'required|string|max:255',
            'ownerAndDirectors.*.present_address' => 'required|string|max:255',
            'ownerAndDirectors.*.phone_number' => 'required|string|max:20',
            'ownerAndDirectors.*.email' => 'required|string|email|max:255',
            'ownerAndDirectors.*.designation' => 'required|string|max:255',
            'ownerAndDirectors.*.relation_with_other_org' => 'nullable|string|max:255',
        ];
    }

}
