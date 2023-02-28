<?php

namespace App\Http\Controllers\API;

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
        $category['user_id'] = 1;
        Category::create($category);
        return $this->postSuccess($category);
    }

    public function show($slug)
    {
        return $this->getData(Category::with('comics:name,slug,image_preview')->where('slug', $slug)->first());
    }

    public function update(UpdateCategoryRequest $request, $slug)
    {
        $category = $request->validated();
        $category['user_id'] = 1;
        Category::where('slug', $slug)->update($category);
        return $this->postSuccess($category, 'Chỉnh sửa thành công!');
    }

    public function delete($slug)
    {
        Category::where('slug', $slug)->delete();
        return $this->postSuccess(false, 'Đã chuyển vào thùng rác!');
    }

    public function destroy($slug)
    {
        $category = Category::where('slug', $slug);
        if ($category->exists())
        {
            $category->forceDelete();
            return $this->postSuccess(false, 'Xoá thành công!');
        }
    }
}
