<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScreeningRequest extends FormRequest
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
            'auditorium_id' => ['sometimes', 'exists:auditoria,id'],
            'movie_id' => ['sometimes', 'exists:movies,id'],
            'language' => ['sometimes', 'string', 'max:50'],
            'start_time' => ['sometimes', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
