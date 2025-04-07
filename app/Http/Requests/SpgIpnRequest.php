<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SpgIpnRequest extends FormRequest
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
            'credential' => 'required|array',
            'credential.username' => 'required|string|min:3|max:255',
            'credential.password' => 'required|string|min:6',

            'data' => 'required|array',
            'data.session_token' => 'required|string|max:1000',
            'data.transactionid' => 'required|string|max:255',
            'data.invoiceno' => 'required|string|max:255',
            'data.invoicedate' => 'required|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'credential.required' => 'The credential field is required.',
            'credential.array' => 'The credential field must be an array.',
            'credential.username.required' => 'The username is required.',
            'credential.password.required' => 'The password is required.',
            'data.required' => 'The data field is required.',
            'data.array' => 'The data field must be an array.',
            'data.session_token.required' => 'The session token is required.',
            'data.transactionid.required' => 'The transaction ID is required.',
            'data.invoiceno.required' => 'The invoice number is required.',
            'data.invoicedate.required' => 'The invoice date is required.',
            'data.invoicedate.date_format' => 'The invoice date must be in the format YYYY-MM-DD.',
        ];
    }
}
