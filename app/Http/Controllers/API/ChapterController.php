<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use ImageKit\ImageKit;
use App\Http\Requests\Chapter\StoreChapterRequest;

class ChapterController extends Controller
{

    public function store(StoreChapterRequest $request)
    {
        $chapter = $request->validated();

        array_unshift($chapter['image_paths'], '');
        unset($chapter['image_paths'][0]);
        $count = count($chapter['image_paths']);
        $arr_images = '';

        $imageKit = new ImageKit(config("imagekit.public_key"), config("imagekit.private_key"), config("imagekit.url_endpoint"));
        for ($i=1; $i<=$count; $i++)
        {
            $uploadFile = $imageKit->uploadFile([
                'file' => base64_encode(file_get_contents($chapter['image_paths'][$i]->path())),
                'fileName' => $i . "." . $chapter['image_paths'][$i]->extension(),
                'folder' => $chapter['comic_id'] . "/" . $chapter['chapter_no'],
                'useUniqueFileName' => false
            ]);
            $arr_images .= $uploadFile->result->name . ",";
        }
        $chapter['image_paths'] = trim($arr_images, ",");

        Chapter::create($chapter);
        return response()->json([
            'code' => 201,
            'message' => 'Thêm mới thành công!'
        ], 201);
    }

    public function storeDirectory()
    {
        //
    }
}
