<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Cloudinary\Api\Admin\AdminApi;

use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    public function index()
    {
        return response()->json(Post::all());
    }

    public function trashed()
    {
        return response()->json(Post::onlyTrashed()->get());
    }

    public function store(StorePostRequest $request)
    {
        $post = $request->validated();
        $post['slug'] = Str::slug($post['name']);
        $post['user_id'] = 1;

        if(isset($post['youtube_embed_code'])) {
            $post['preview'] = $post['youtube_embed_code'];
            $post = Arr::except($post, ['youtube_embed_code']);
        }
        else if ($post['preview']) {
            array_unshift($post['preview'],'');
            unset($post['preview'][0]);
            $count = count($post['preview']);
            $preview = '';
            //Store array files into Cloudinary
            for ($i = 1; $i<=$count; $i++) {
                $result = $post['preview'][$i]->storeOnCloudinaryAs($post['slug'], $i);
                $preview .= $i . "." . $result->getExtension() . ",";
            }
            $post['preview'] = trim($preview, ",");
        }

        Post::create($post);
        return response()->json([
            'code' => 201,
            'message' => 'Thêm mới thành công!'
        ], 201);
    }

    public function show($slug)
    {
        return response()->json(Post::with('category:id,name')->where('slug', $slug)->first());
    }

    public function update(UpdatePostRequest $request, $slug)
    {
        $post = $request->validated();
        $post['slug'] = Str::slug($post['name']);
        $post['user_id'] = 1;

        $adminApi = new AdminApi(config("cloudinary.cloud_url"));
        if ($post['slug'] != $slug) {
            $adminApi->deleteAssetsByPrefix($slug);
            $adminApi->deleteFolder($slug);
            $adminApi->createFolder($post['slug']);
        }

        if(isset($post['youtube_embed_code'])) {
            $post['preview'] = $post['youtube_embed_code'];
            $post = Arr::except($post, ['youtube_embed_code']);
        } else if ($post['preview']) {
            array_unshift($post['preview'],'');
            unset($post['preview'][0]);
            $count = count($post['preview']);
            $preview = '';
            // Remove old files Cloudinary
            $adminApi->deleteAssetsByPrefix($post['slug']);
            // Add array files into Cloudinary
            for ($i = 1; $i<=$count; $i++) {
                $result = $post['preview'][$i]->storeOnCloudinaryAs($post['slug'], $i);
                $preview .= $i . "." . $result->getExtension() . ",";
            }
            $post['preview'] = trim($preview, ",");
        }

        Post::where('slug', $slug)->update($post);
        return response()->json([
            'code' => 201,
            'message' => 'Chỉnh sửa thành công!'
        ], 201);
    }

    public function delete($slug)
    {
        Post::where('slug', $slug)->delete();
        return response()->json([
            'code' => 201,
            'message' => 'Đã chuyển vào thùng rác!'
        ], 201);
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        if ($post->exists()) {
            $adminApi = new AdminApi(config("cloudinary.cloud_url"));
            $adminApi->deleteAssetsByPrefix($slug);
            $adminApi->deleteFolder($slug);
            $post->forceDelete();
            return response()->json([
                'code' => 204,
                'message' => 'Xoá thành công!'
            ], 204);
        }
    }
}
