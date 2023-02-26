<?php

namespace App\Http\Requests\Category;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends BaseRequest
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
                'min:5',
                'max:50',
                Rule::unique('categories')->where(fn ($query) => $query->where('type', request()->type)),
            ],
            'desc' => [
                'required',
                'min:10',
                'max:300'
            ],
            'type' => [
                'required',
                'numeric'
            ],
        ];
    }
}
