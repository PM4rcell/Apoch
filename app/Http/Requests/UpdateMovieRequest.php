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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'title' => 'sometimes|string|max:255',
            'imdb_id' => 'sometimes|string|max:20|unique:movies,imdb_id',
            'age_rating' => 'sometimes|string|max:10',
            'vote_avg' => 'sometimes|numeric|min:0|max:10',
            'description' => 'sometimes|string',
            'release_date' => 'sometimes|date',
            'runtime_min' => 'sometimes|integer|min:1',
            'director' => 'sometimes|string|max:100',
            'era' => 'sometimes|string|max:100',
            'trailer_link' => 'sometimes|url',

            'genres' => 'sometimes|array|min:1',
            'genres.*' => 'sometimes|string|max:40',

            'cast' => 'sometimes|array|min:1',
            'cast.*.name' => 'sometimes|string|max:100',
            'cast.*.role' => 'sometimes|string|max:100',

            'omdb_poster_url' => ['sometimes', 'url'],
            'poster_file'     => ['sometimes', 'image', 'max:4096'], 

            'gallery'   => ['sometimes', 'array'],
            'gallery.*' => ['sometimes', 'max:4096'],
        ];
    }
}
