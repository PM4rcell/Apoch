<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'imdb_id' => 'nullable|string|max:20|unique:movies,imdb_id',
            'age_rating' => 'nullable|string|max:10',
            'vote_avg' => 'nullable|numeric|min:0|max:10',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'runtime_min' => 'required|integer|min:1',
            'director_id' => 'required|string|max:100',
            'era_id' => 'required|exists:eras,id',
            'trailer_link' => 'nullable|url',

            'genres' => 'required|array|min:1',
            'genres.*' => 'required|string|max:40',

            'cast' => 'required|array|min:1',
            'cast.*.name' => 'required|string|max:100',
            'cast.*.role' => 'required|string|max:100',

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
            'poster_file'     => ['nullable', 'image','mimes:jpg,jpeg,png,webp', 'max:4096'], 
            
            'gallery_files' => ["nullable", "array", 'max:10'],
            'gallery_urls' => ["nullable", "array", 'max:10'],
            'gallery_files.*' => ["image", 'max:4096', 'mimes:jpg,jpeg,png,webp',],
            'gallery_urls.*' => ["url",
                function ($attribute, $value, $fail) {
                    if (! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://')) {
                        $fail('Only http/https URLs are allowed.');
                    }
            
                    if (preg_match('/^(https?:\/\/)(localhost|127\.0\.0\.1|10\.|192\.168\.)/i', $value)) {
                        $fail('Local URLs are not allowed.');
                    }
                }
            ],
        ];
    }
}
