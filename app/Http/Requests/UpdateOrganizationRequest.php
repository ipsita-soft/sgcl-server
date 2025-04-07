<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizationRequest extends FormRequest
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
            'organization_category_id' => 'required|exists:organization_categories,id',
            'factory_name' => 'required|string|max:255',
            'factory_address' => 'required|string|max:255',
            'factory_telephone' => 'required|string|max:20',
            'main_office_address' => 'required|string|max:255',
            'main_office_telephone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:255',
            'billing_telephone' => 'required|string|max:20',
            'mobile' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
            'national_id' => 'required|string|max:20',
            'tax_identification_no' => 'required|string|max:20',
            'gis_location' => 'required|string|max:255',
            'organization_ownership_type_id' => 'required|exists:organization_ownership_types,id',
            'industry_type_id' => 'required|exists:industry_types,id',
            'trade_license_no' => 'required|integer',
            'license_expiry_date' => 'required|date',
        ];
    }
}
