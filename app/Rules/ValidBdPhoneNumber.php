<?php

namespace App\Rules;

use App\Api\FailedValidation;
use Illuminate\Contracts\Validation\Rule;

class ValidBdPhoneNumber implements Rule
{
    use FailedValidation;
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if phone number starts with '+880' and replace it with '0'
        if (substr($value, 0, 4) === '+880') {
            $value = '0' . substr($value, 4);
        }

        // Ensure the phone number is exactly 11 digits and starts with '0'
        return preg_match('/^0[0-9]{10}$/', $value) && strlen($value) === 11;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid Bangladeshi phone number with exactly 11 digits.';
    }
}
