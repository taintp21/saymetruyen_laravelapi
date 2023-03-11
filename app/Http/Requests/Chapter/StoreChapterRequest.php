<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class StoreChapterRequest extends FormRequest
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
            'comic_id' => [
                'required',
                'exists:comics,id'
            ],
            'name' => [
                'nullable',
                'string',
                'min:3',
                'max:100'
            ],
            'chapter_no' => [
                'required',
                'numeric',
                'min:0',
                'not_regex:/[eE]/'
            ],
            'image_paths' => [
                'required',
                'array',
            ],
            'image_paths.*' => [
                'image',
                'mimetypes:image/jpeg,image/png'
            ],
        ];
    }
}
