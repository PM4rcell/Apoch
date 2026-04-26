<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email',
            'name' => 'min:2',
            'card_number' => [                
                function ($attribute, $value, $fail) {
                    $cleaned = preg_replace('/\D/', '', $value);
                    if (strlen($cleaned) !== 16) {
                        $fail('The card number must be exactly 16 digits.');
                    }
                },
            ],
            'expiry' => [
                'regex:/^\d{2}\/\d{2}$/',
                function ($attribute, $value, $fail) {
                    [$month, $year] = explode('/', $value);
                    $month = (int) $month;
                    $year = (int) $year;

                    if ($month < 1 || $month > 12) {
                        $fail('The expiry month must be between 01 and 12.');
                    }

                    $currentMonth = (int) date('m');
                    $currentYear = (int) date('y'); // Last two digits

                    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                        $fail('The expiry date cannot be in the past.');
                    }
                },
            ],
            'cvc' => 'regex:/^\d{3,4}$/',
            'country' => 'min:2',
            'zip' => 'regex:/^[A-Za-z0-9\s-]{3,10}$/',
        ];
    }
}
