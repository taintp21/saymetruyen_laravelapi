<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;

use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends BaseController
{
    public function index()
    {
        return $this->getData(Category::all());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $request->validated();
        $category['slug'] = Str::slug($category['name']);
        $category['user_id'] = 1;
        Category::create($category);
        return $this->postSuccess($category);
    }

    public function show($slug)
    {
        return $this->getData(Category::with('comics:name,slug,image_preview')->where('slug', $slug)->first());
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $updateCategory = $request->validated();
        $updateCategory['slug'] = Str::slug($updateCategory['name']);
        $updateCategory['user_id'] = 1;
        Category::where('id', $id)->update($updateCategory);
        return $this->postSuccess($updateCategory, 'Chỉnh sửa thành công!');
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
