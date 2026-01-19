<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
            'imdb_id' => 'required|string|max:20|unique:movies,imdb_id',
            'age_rating' => 'required|string|max:10',
            'vote_avg' => 'required|numeric|min:0|max:10',
            'description' => 'required|string',
            'release_date' => 'required|date',
            'runtime_min' => 'required|integer|min:1',
            'director' => 'required|string|max:100',
            'era' => 'required|string|max:100',
            'trailer_link' => 'required|url',

            'genres' => 'required|array|min:1',
            'genres.*' => 'required|string|max:40',

            'cast' => 'required|array|min:1',
            'cast.*.name' => 'required|string|max:100',
            'cast.*.role' => 'required|string|max:100',

            'external_url' => ['nullable', 'url'],
            'poster_file'     => ['nullable', 'image', 'max:4096'], 

            'gallery'   => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'max:4096'],            
        ];
    }
}
