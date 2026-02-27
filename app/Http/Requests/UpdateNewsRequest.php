<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category' => 'required|string|max:100',
            'excerp' => 'required|string',
            'read_time_min' => 'required|integer|min:1',
            'external_link' => 'nullable|url',   

            'external_poster_url' => ['nullable','url',
                function ($attribute, $value, $fail) {
                    if (! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://')) {
                        $fail('Only http/https URLs are allowed.');
                    }
            
                    if (preg_match('/^(https?:\/\/)(localhost|127\.0\.0\.1|10\.|192\.168\.)/i', $value)) {
                        $fail('Local URLs are not allowed.');
                    }
                }
            ],
            'poster_file' => ['nullable','image','max:4096','mimes:jpg,jpeg,png,webp']
        ];
    }
}
