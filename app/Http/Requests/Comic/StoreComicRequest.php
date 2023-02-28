<?php

namespace App\Http\Requests\Comic;

use Illuminate\Foundation\Http\FormRequest;

class StoreComicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:200',
                'unique:comics,slug'
            ],
            'another_names' => [
                'nullable',
                'max:300'
            ],
            'author' => [
                'required',
                'string',
                'max:50'
            ],
            'desc' => [
                'nullable'
            ],
            'background_preview' => [
                'required',
                'image',
                'mimetypes:image/jpeg,image/png'
            ],
            'image_preview' => [
                'required',
                'image',
                'mimetypes:image/jpeg,image/png'
            ],
            'category_id' => [
                'required',
                'numeric'
            ],
        ];
    }
}
