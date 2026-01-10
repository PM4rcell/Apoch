<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin();   
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'screening_id' => 'required|exists:screenings,id',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'nullable|email',
            'booking_fee' => 'required|numeric|min:1',
            'status' => 'required|string|in:pending,paid,cancelled'
        ];
    }
}
