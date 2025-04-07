<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class OauthToken extends FormRequest
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
            'clientId' => 'required|string|max:255',
            'clientSecret' => 'required|string|max:255',
        ];
    }
}
