<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingLockRequest extends FormRequest
{
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
            "screening_id" => 'required|exists:screenings,id',
            "seat_ids" => 'required|array|min:1',
            "seat_ids.*" => 'integer|exists:seats,id',            
            "ticket_type_id" => 'required|exists:ticket_types,id',
            "customer.mode" => 'required|in:user,guest',
            'customer.email' => 'required_if:customer.mode,guest|email',            
        ];
    }
}
