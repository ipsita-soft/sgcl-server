<?php

namespace App\Http\Requests\Forms;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class UpdateIngredientsInfoProductionRequest extends FormRequest
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
            'ingredientsInfoProduction.*.organization_id' => 'required|exists:organizations,id',
            'ingredientsInfoProduction.*.goods_name' => 'required|string',
            'ingredientsInfoProduction.*.yearly_production' => 'required|string',
            'ingredientsInfoProduction.*.where_sold' => 'required|string',
        ];
    }
}
