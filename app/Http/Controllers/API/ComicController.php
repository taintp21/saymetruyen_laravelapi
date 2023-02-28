<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;

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

    public function update(UpdateComicRequest $request, $slug)
    {
        $comic = $request->validated();
        $comic['slug'] = Str::slug($comic['name']);
        $comic['user_id'] = 1;
        $currentComic = Comic::find($slug);
        $currentComic->update($comic);
        $currentComic->categories()->sync(explode(',', $comic['category_id']));
        return $this->postSuccess($currentComic, 'Chỉnh sửa thành công!');
    }

    public function delete($slug)
    {
        Comic::where('slug', $slug)->delete();
        return $this->postSuccess(false, 'Xoá thành công!');
    }

    public function destroy($slug)
    {
        $comic = Comic::where('slug', $slug);
        if ($comic->exists())
        {
            $comic->forceDelete();
            return $this->postSuccess(false, 'Xoá thành công!');
        }
    }
}
