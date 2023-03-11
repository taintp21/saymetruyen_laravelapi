<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(Category::all());
    }

    public function trashed()
    {
        return response()->json(Category::onlyTrashed()->get());
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $request->validated();
        $category['user_id'] = 1;
        Category::create($category);
        return response()->json([
            'code' => 201,
            'message' => 'Thêm mới thành công!'
        ], 201);
    }

    public function show($slug)
    {
        return response()->json(Category::with('comics:name,slug,image_preview')->where('slug', $slug)->first());
    }

    public function update(UpdateCategoryRequest $request, $slug)
    {
        $category = $request->validated();
        $category['user_id'] = 1;
        Category::where('slug', $slug)->update($category);
        return response()->json([
            'code' => 201,
            'message' => 'Chỉnh sửa thành công!'
        ], 201);
    }

    public function delete($slug)
    {
        Category::where('slug', $slug)->delete();
        return response()->json([
            'code' => 201,
            'message' => 'Đã chuyển vào thùng rác!'
        ], 201);
    }

    public function destroy($slug)
    {
        $category = Category::where('slug', $slug);
        if ($category->exists())
        {
            $category->forceDelete();
            return response()->json([
                'code' => 204,
                'message' => 'Xoá thành công!'
            ], 204);
        }
    }
}
