<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

use App\Models\Comic;
use App\Http\Requests\Comic\StoreComicRequest;
use App\Http\Requests\Comic\UpdateComicRequest;

class ComicController extends BaseController
{
    public function index()
    {
        return $this->getData(Comic::all());
    }

    public function store(StoreComicRequest $request)
    {
        $comic = $request->validated();
        $comic['slug'] = Str::slug($comic['name']);
        $comic['user_id'] = 1;
        $storeComic = Comic::create($comic);
        $storeComic->categories()->attach(explode(',', $comic['category_id']));
        return $this->postSuccess($storeComic);
    }

    public function show($slug)
    {
        return $this->getData(Comic::with('categories:name', 'chapters')->where('slug', $slug)->first());
    }

    public function update(UpdateComicRequest $request, $id)
    {
        $comic = $request->validated();
        $comic['slug'] = Str::slug($comic['name']);
        $comic['user_id'] = 1;
        $updateComic = Comic::find($id);
        $updateComic->update($comic);
        $updateComic->categories()->sync(explode(',', $comic['category_id']));
        return $this->postSuccess($updateComic, 'Chỉnh sửa thành công!');
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
