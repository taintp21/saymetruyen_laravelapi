<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Str;

use App\Http\Requests\Comic\StoreComicRequest;
use App\Http\Requests\Comic\UpdateComicRequest;
use App\Models\Comic;
use ImageKit\ImageKit;

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

        $imageKit = new ImageKit(config("imagekit.public_key"), config("imagekit.private_key"), config("imagekit.url_endpoint"));
        $uploadBgPreview = $imageKit->uploadFile([
            'file' => base64_encode(file_get_contents($comic['background_preview']->path())),
            'fileName' => $comic['slug'] . "_bg",
            'useUniqueFileName' => false
        ]);
        $uploadImgPreview = $imageKit->uploadFile([
            'file' => base64_encode(file_get_contents($comic['image_preview']->path())),
            'fileName' => $comic['slug'] . "_img",
            'useUniqueFileName' => false
        ]);

        $comic['background_preview'] = $uploadBgPreview->result->name;
        $comic['image_preview'] = $uploadImgPreview->result->name;
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

        $imageKit = new ImageKit(config("imagekit.public_key"), config("imagekit.private_key"), config("imagekit.url_endpoint"));
        if ($comic['slug'] != $slug) {
            $imageKit->rename([
                "filePath" => $slug . "_bg",
                "newFileName" => $comic['slug'] . "_bg",
                "purgeCache" => false
            ]);
            $imageKit->rename([
                "filePath" => $slug . "_img",
                "newFileName" => $comic['slug'] . "_img",
                "purgeCache" => false
            ]);
        }
        $uploadBgPreview = $imageKit->uploadFile([
            'file' => base64_encode(file_get_contents($comic['background_preview']->path())),
            'fileName' => $comic['slug'] . "_bg",
            'useUniqueFileName' => false
        ]);
        $uploadImgPreview = $imageKit->uploadFile([
            'file' => base64_encode(file_get_contents($comic['image_preview']->path())),
            'fileName' => $comic['slug'] . "_img",
            'useUniqueFileName' => false
        ]);

        $comic['background_preview'] = $uploadBgPreview->result->name;
        $comic['image_preview'] = $uploadImgPreview->result->name;

        Comic::where('slug', $slug)->update($comic);
        Comic::find($slug)->categories()->sync(explode(',', $comic['category_id']));
        return $this->postSuccess($comic, 'Chỉnh sửa thành công!');
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
            $imageKit = new ImageKit(config("imagekit.public_key"), config("imagekit.private_key"), config("imagekit.url_endpoint"));
            $searchBg = $imageKit->listFiles([
                'searchQuery' => $slug . "_bg"
            ]);
            $searchImg = $imageKit->listFiles([
                'searchQuery' => $slug . "_img"
            ]);
            $imageKit->bulkDeleteFiles([$searchBg->result->fileId, $searchImg->result->fileId]);
            $comic->forceDelete();
            return $this->postSuccess(false, 'Xoá thành công!');
        }
    }
}
