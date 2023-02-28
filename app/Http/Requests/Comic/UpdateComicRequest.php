<?php

namespace App\Http\Requests\Comic;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComicRequest extends FormRequest
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
                'max:200',
                'unique:comics,slug,'.$this->route('truyen_tranh')
            ],
            'another_names' => [
                'nullable',
                'max:300'
            ],
            'author' => [
                'required',
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
            'user_id' => [
                'required'
            ],
            'category_id' => [
                'required'
            ]
        ];
    }
}
