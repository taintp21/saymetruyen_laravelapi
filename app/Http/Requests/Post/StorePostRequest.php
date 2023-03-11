<?php

namespace App\Http\Requests\Post;

use App\Http\Requests\BaseRequest;

class StorePostRequest extends BaseRequest
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
                'min:20',
                'max:200',
                'unique:posts,name'
            ],
            'preview' => [
                'required_without:youtube_embed_code', // Nếu youtube_embed_code field empty thì field này phải có value.
                'prohibits:youtube_embed_code', // youtube_embed_code field phải để trống nếu field này có value.
                'array',
            ],
            'preview.*' => [
                'image',
                'mimetypes:image/jpeg,image/png'
            ],
            'youtube_embed_code' => [
                'required_without:preview',
                'prohibits:preview',
                'string',
                'alpha_dash'
            ],
            'body' => [
                'required',
                'string',
                'min:50'
            ],
            'category_id' => [
                'nullable',
                'numeric',
                'exists:categories,id'
            ],
            // 'user_id' => [
            //     'required',
            //     'exists:users,id'
            // ]
        ];
    }
}
