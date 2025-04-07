<?php
namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    use FailedValidation;
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'The old password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.min' => 'The new password must be at least 8 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
