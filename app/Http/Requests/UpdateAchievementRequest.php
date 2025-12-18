<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAchievementRequest extends FormRequest
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
         $achievement = $this->route('achievement');
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('achievements', 'name')->ignore($achievement->id),
            ],
            'description' => 'required|string',
            'type' => 'required|string',
            'points' => 'required|int|max:9999',
            'year' => 'required|int',

            'external_url' => ['nullable', 'url'],
            'poster_file'     => ['nullable', 'image', 'max:4096'], 
        ];
    }
}
