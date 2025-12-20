<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeatRequest extends FormRequest
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
            'auditorium_id' => 'required|exists:auditoria,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'row' => 'required|int|min:1',
            'number' => 'required|int|min:1'
        ];
    }
}
