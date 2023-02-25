<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;

class PostController extends BaseController
{
    public function index()
    {
        return $this->getData(Post::all());
    }

    public function store(Request $request)
    {
        if(count($request->all()) <= 0) return $this->validatorFails('Không có dữ liệu!');
        $storeRequest = $request->only(['name', 'preview', 'body', 'category_id']);
        $storeRequest['slug'] = Str::slug($storeRequest['name']);
        $storeRequest['user_id'] = 1;
        $validator = Validator::make($storeRequest, [
            'name' => 'required|string|min:20|max:200|unique:posts,name',
            'preview' => 'required|'. $this->validateImageOrString($storeRequest['preview']),
            'body' => 'required|string|min:50',
            'category_id' => 'required|numeric',
        ]);
        if ($validator->fails()) return $this->validatorFails($validator->messages());
        if(file_exists($validator->valid()['preview'])) {
        }
        // Post::create($validator->valid());
        // return $this->postSuccess($validator->valid());
    }

    public function show($slug)
    {
        return $this->getData(Post::with('category:name')->where('slug', $slug)->first());
    }

    public function update(Request $request, $id)
    {
        if(count($request->all()) <= 0) return $this->validatorFails('Không có dữ liệu!');

        $updateRequest = $request->only(['name', 'preview', 'body', 'category_id']);
        $updateRequest['slug'] = Str::slug($updateRequest['name']);
        $updateRequest['user_id'] = 1;

        $validator = Validator::make([
            'name' => 'required|min:20|max:200|unique:posts,name,'.$id,
            'preview' => 'required|'.$this->validateImageOrString($request->hasFile('preview')),
            'body' => 'required|min:100',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) return $this->validatorFails($validator->messages());
        Post::where('id', $id)->update($validator->valid());
        return $this->postSuccess($validator->valid(), 'Chỉnh sửa thành công!');
    }

    public function delete($id)
    {
        Post::where('id', $id)->delete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }

    public function destroy($id)
    {
        Post::where('id', $id)->forceDelete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }
}
