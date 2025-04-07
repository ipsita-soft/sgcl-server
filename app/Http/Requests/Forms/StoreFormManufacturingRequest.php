<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreFormManufacturingRequest extends FormRequest
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
            'organization_id' => 'required|exists:organizations,id|unique:manufacturing_data,organization_id',
            'production_type_id' => 'nullable|exists:production_types,id',
            'factory_starting_time' => 'nullable|date_format:H:i:s',
            'factory_ending_time' => 'nullable|date_format:H:i:s',
        ];
    }
}
