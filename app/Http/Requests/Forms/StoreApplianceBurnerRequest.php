<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplianceBurnerRequest extends FormRequest
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
            'applianceBurner.*.organization_id' => 'required|integer|exists:organizations,id',
            'applianceBurner.*.appliance_name' => 'required|string',
            'applianceBurner.*.appliance_size' => 'required|string',
            'applianceBurner.*.appliance_production_capacity' => 'required|string',
            'applianceBurner.*.burner_type' => 'required|string',
            'applianceBurner.*.burner_count' => 'required|string',
            'applianceBurner.*.burner_capacity' => 'required|string',
            'applianceBurner.*.total_load' => 'required|numeric',
            'applianceBurner.*.comments' => 'nullable|string',
        ];
    }
}
