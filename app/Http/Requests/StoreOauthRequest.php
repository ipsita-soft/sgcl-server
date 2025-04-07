<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidBdPhoneNumber;

class StoreOauthRequest extends FormRequest
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
            'bida_oss_id' => 'required|string|max:255|unique:users,bida_oss_id',
            'organization_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'authorized_person_name' => 'required|string|max:255',
            'authorized_person_mobile_no' => ['required', 'string', new ValidBdPhoneNumber],
        ];
    }

    public function messages()
    {
        return [
            'bida_oss_id.required' => 'The BIDA OSS ID is required.',
            'organization_name.required' => 'The organization name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'The email address must be a valid email format.',
            'email.unique' => 'The email address has already been taken.',
            'authorized_person_name.required' => 'The authorized person\'s name is required.',
            'authorized_person_mobile_no.required' => 'The authorized person\'s mobile number is required.',
            'authorized_person_name.size' => 'The authorized person\'s mobile number must be exactly 11 digits.',
            'clientInfo.clientId.required' => 'The client ID is required.',
            'clientInfo.clientId.uuid' => 'The client ID must be a valid UUID.',
            'clientInfo.clientSecret.required' => 'The client secret is required.',
        ];
    }
}
