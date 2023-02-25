<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Comic;

class ComicController extends BaseController
{
    public function index()
    {
        return $this->getData(Comic::all());
    }

    public function store(Request $request)
    {
        if(count($request->all()) <= 0) return $this->validatorFails('Không có dữ liệu!');

        $storeRequest = $request->only(['name', 'author', 'desc', 'background_preview', 'image_preview', 'category_id']);
        $storeRequest['slug'] = Str::slug($storeRequest['name']);
        $storeRequest['user_id'] = 1;

        $validator = Validator::make($storeRequest,[
            'name' => 'required|string|max:200|unique:comics,name',
            'author' => 'required|string|max:50',
            'desc' => 'nullable',
            'background_preview' => 'required|max:500',
            'image_preview' => 'required|max:500',
            'category_id' => 'required|numeric',
        ]);

        if ($validator->fails()) return $this->validatorFails($validator->messages());

        $comic = Comic::create($validator->valid());
        $comic->categories()->attach(explode(',', $validator->valid()['category_id']));
        return $this->postSuccess($comic);
    }

    public function show($slug)
    {
        return $this->getData(Comic::with('categories:name', 'chapters')->where('slug', $slug)->first());
    }

    public function update(Request $request, $id)
    {
        if(count($request->all()) <= 0) return $this->validatorFails('Không có dữ liệu!');

        $updateRequest = $request->only(['name', 'author', 'desc', 'background_preview', 'image_preview', 'category_id']);
        $updateRequest['slug'] = Str::slug($updateRequest['name']);
        $updateRequest['user_id'] = 1;

        $validator = Validator::make($updateRequest,[
            'name' => 'required|max:200|unique:comics,name,'.$id,
            'author' => 'required|max:50',
            'desc' => 'nullable',
            'background_preview' => 'required|max:500',
            'image_preview' => 'required|max:500',
            'user_id' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) return $this->validatorFails($validator->messages());

        $comic = Comic::find($id);
        $comic->update($validator->valid());
        $comic->categories()->sync(explode(',', $validator->valid()['category_id']));
        return $this->postSuccess($comic, 'Chỉnh sửa thành công!');
    }

    public function delete($id)
    {
        Comic::where('id', $id)->delete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }

    public function destroy($id)
    {
        Comic::where('id', $id)->forceDelete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }
}
