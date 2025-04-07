<?php

namespace App\Http\Requests;

use App\Api\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRemindersRequest extends FormRequest
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
            'message' => 'required|string',
            'amount' => 'required|numeric',
            'date' => 'required|date_format:Y-m-d',
            'sender' => 'nullable|integer',
            'remindersData' => 'required|array',
            'remindersData.*.send_to' => 'required|integer',
        ];
    }
}
