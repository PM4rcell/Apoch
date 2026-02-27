<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $targetId = $this->user()->id;
        $isAdmin  = $this->user()->isAdmin();

        $rules = 
        [
            'username' => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|max:255|unique:users,email,' . $targetId, 
            'watchlist' => 'sometimes|array',
            'watchlist.*' => 'integer|exists:movies,id',            
            'external_url' => ['nullable', 'url', 
                function ($attribute, $value, $fail) {
                    if (! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://')) {
                        $fail('Only http/https URLs are allowed.');
                    }
            
                    if (preg_match('/^(https?:\/\/)(localhost|127\.0\.0\.1|10\.|192\.168\.)/i', $value)) {
                        $fail('Local URLs are not allowed.');
                    }
                }
            ],
            'poster_file'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], 
        ];

        if($isAdmin)
        {
            $rules['points'] = 'sometimes|integer|min:0';
        }

        return $rules;
    }
}
