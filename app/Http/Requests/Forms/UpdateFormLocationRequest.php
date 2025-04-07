<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateFormLocationRequest extends FormRequest
{
    use \App\Api\FailedValidation;

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
            'mouza_name' => 'required|string',
            'daag_no' => 'required|string',
            'khotiyan_no' => 'required|string',
            'total_land_area' => 'required|string',
            'land_ownership_id' => 'required|exists:land_ownerships,id',
            'land_width_feet' => 'required|integer',
            'land_length_feet' => 'required|integer',

            'owner_name_ifRented' => 'nullable|string',
            'owner_address_ifRented' => 'nullable|string',
            'lease_provider_organization_name_Ifleased' => 'nullable|string',
            'lease_provider_organization_address_if_leased' => 'nullable|string',

            'any_other_customer_used_gas' => 'nullable|integer|in:1,2',
            'customer_code_no' => 'nullable|integer',
            'organization_name' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'connection_status' => 'nullable|integer|in:1,2,3',
            'clearance_of_gas_bill' => 'nullable|integer|in:1,2',

            'is_organization_owner' => 'nullable|integer|in:1,2',
            'owner_partner_code' => 'nullable|string',
            'owner_partner_name' => 'nullable|string',
            'owner_partner_status' => 'nullable|string',
        ];
    }
}
