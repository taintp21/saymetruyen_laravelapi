<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

use App\Models\Comic;
use ImageKit\ImageKit;
use App\Http\Requests\Comic\StoreComicRequest;
use App\Http\Requests\Comic\UpdateComicRequest;

class ComicController extends Controller
{
    public function index()
    {
        return response()->json(Comic::all());
    }

    public function trashed()
    {
        return response()->json(Comic::onlyTrashed()->get());
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
        return response()->json([
            'code' => 201,
            'message' => 'Thêm mới thành công!'
        ], 201);
    }

    public function show($slug)
    {
        return response()->json(Comic::with('categories:name', 'chapters')->where('slug', $slug)->first());
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
                "purgeCache" => true
            ]);
            $imageKit->rename([
                "filePath" => $slug . "_img",
                "newFileName" => $comic['slug'] . "_img",
                "purgeCache" => true
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
        $imageKit->purgeCache($uploadBgPreview->result->url);
        $imageKit->purgeCache($uploadImgPreview->result->url);

        $comic['background_preview'] = $uploadBgPreview->result->name;
        $comic['image_preview'] = $uploadImgPreview->result->name;

        $oldRecord = Comic::find($slug);
        $oldRecord->update($comic);
        $oldRecord->categories()->sync(explode(',', $comic['category_id']));
        return response()->json([
            'code' => 201,
            'message' => 'Chỉnh sửa thành công!'
        ], 201);
    }

    public function delete($slug)
    {
        Comic::where('slug', $slug)->delete();
        return response()->json([
            'code' => 201,
            'message' => 'Đã chuyển vào thùng rác!'
        ], 201);
    }

    public function destroy($slug)
    {
        $comic = Comic::find($slug);
        if ($comic)
        {
            $imageKit = new ImageKit(config("imagekit.public_key"), config("imagekit.private_key"), config("imagekit.url_endpoint"));
            $searchBg = $imageKit->listFiles([
                'searchQuery' => $slug . "_bg"
            ]);
            $searchImg = $imageKit->listFiles([
                'searchQuery' => $slug . "_img"
            ]);
            $imageKit->bulkDeleteFiles([$searchBg->result->fileId, $searchImg->result->fileId]);
            $comic->categories()->detach();
            $comic->forceDelete();
            return response()->json([
                'code' => 204,
                'message' => 'Xoá thành công!'
            ], 204);
        }
    }
}
