<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDirectorRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'external_url' => ['nullable','url',
                function ($attribute, $value, $fail) {
                    if (! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://')) {
                        $fail('Only http/https URLs are allowed.');
                    }
            
                    if (preg_match('/^(https?:\/\/)(localhost|127\.0\.0\.1|10\.|192\.168\.)/i', $value)) {
                        $fail('Local URLs are not allowed.');
                    }
                }
            ],
            'poster_file' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }
}
