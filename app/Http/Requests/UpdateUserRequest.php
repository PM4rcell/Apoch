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
            'avatar' => ['nullable', 'image', 'max:4096'],
        ];

        if($isAdmin)
        {
            $rules['points'] = 'sometimes|integer|min:0';
        }

        return $rules;
    }
}
