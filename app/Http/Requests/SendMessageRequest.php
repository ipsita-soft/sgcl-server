<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|max:1000',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,doc,docx,pdf,txt|max:2048',
        ];
    }
}
