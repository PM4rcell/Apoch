<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicket_typeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:ticket_types,name',
                Rule::unique('ticket_types', 'name')->ignore($this->ticketType)
            ],
            'price' => 'required|numeric|min:1',
            'point_price' => 'required|int|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

            'poster' => 'sometimes|img|max:4096|mimes:png,jpg,jpeg',
            'external_url' => ['sometimes', 'url', 'max:2048']
        ];
    }
}
