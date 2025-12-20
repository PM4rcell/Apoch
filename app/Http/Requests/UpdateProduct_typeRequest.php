<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProduct_typeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:product_types,name',
                Rule::unique('product_types', 'name')->ignore($this->productType)
            ],
            'price' => 'required|numeric|min:1',
            'point_price' => 'required|int|min:1',
            'era_id' => 'required|exists:eras,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',

            'poster' => 'sometimes|img|max:4096|mimes:png,jpg,jpeg',
            'external_url' => ['sometimes', 'url', 'max:2048']
        ];
    }
}
