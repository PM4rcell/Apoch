<?php

namespace App\Http\Requests;

use App\Rules\NoOverlappingScreening;
use Illuminate\Foundation\Http\FormRequest;

class StoreScreeningRequest extends FormRequest
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
            'auditorium_id' => ['required', 'exists:auditoria,id'],
            'movie_id' => ['required', 'exists:movies,id'],
            'language_id' => ['required', 'exists:languages,id'],
            'start_time' => ['required', 'date_format:Y-m-d H:i:s', new NoOverlappingScreening()],
            'screening_type_id' => ['required', 'integer', 'exists:screening_types,id']
        ];
    }
}
