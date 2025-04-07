<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class StoreFormFinancialInfoRequest extends FormRequest
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
            // 'organization_id' => 'required|exists:organizations,id|unique:financial_information,organization_id',
            'organization_id' => 'required',
            'tax_indentification_no' => 'nullable|string|max:255',
            'bank_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
        ];
    }
}
