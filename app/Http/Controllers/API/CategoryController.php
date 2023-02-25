<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Category;

class CategoryController extends BaseController
{
    public function index()
    {
        return $this->getData(Category::all());
    }

    public function store(Request $request)
    {
        if(count($request->all()) <= 0) return $this->validatorFails('Không có dữ liệu!');

        $storeRequest = $request->only(['name', 'desc', 'type']);
        $storeRequest['slug'] = Str::slug($storeRequest['name']);
        $storeRequest['user_id'] = 1;

        $validator = Validator::make($storeRequest,[
            'name' => 'required|string|min:5|max:50|string',
            'desc' => 'required|min:10|max:300',
            'type' => 'required|numeric',
        ]);

        if ($validator->fails()) return $this->validatorFails($validator->messages());

        Category::create($validator->valid());
        return $this->postSuccess($validator->valid());
    }

    public function show($slug)
    {
        return $this->getData(Category::with('comics:name,slug,image_preview')->where('slug', $slug)->first());
    }

    public function update(Request $request, $id)
    {
        $updateRequest = $request->only(['name', 'desc', 'type']);
        $updateRequest['slug'] = Str::slug($updateRequest['name']);
        $updateRequest['user_id'] = 1;

        $validator = Validator::make($updateRequest,[
            'name' => 'required|min:5|max:50|string',
            'desc' => 'required|min:10|max:300',
            'type' => 'required',
        ]);
        if ($validator->fails()) return $this->validatorFails($validator->messages());
        Category::where('id', $id)->update($validator->valid());
        return $this->postSuccess($validator->valid(), 'Chỉnh sửa thành công!');
    }

    public function delete($id)
    {
        Category::where('id', $id)->delete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }

    public function destroy($id)
    {
        Category::where('id', $id)->forceDelete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }
}
