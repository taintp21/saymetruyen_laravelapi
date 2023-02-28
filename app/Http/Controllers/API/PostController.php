<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Cloudinary\Api\Admin\AdminApi;

use App\Models\Post;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends BaseController
{
    public function index()
    {
        return $this->getData(Post::all());
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
        return $this->postSuccess($post);
    }

    public function show($slug)
    {
        return $this->getData(Post::with('category:name')->where('slug', $slug)->first());
    }

    public function update(UpdatePostRequest $request, $slug)
    {
        $post = $request->validated();
        $post['slug'] = Str::slug($post['name']);
        $post['user_id'] = 1;

        $adminApi = new AdminApi();
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
        return $this->postSuccess($post, 'Chỉnh sửa thành công!');
    }

    public function delete($slug)
    {
        Post::where('slug', $slug)->delete();
        return $this->postSuccess(null, 'Đã chuyển vào thùng rác!');
    }

    public function destroy($slug)
    {
        $post = Post::where('slug', $slug);
        if ($post->exists())
        {
            $adminApi = new AdminApi();
            $adminApi->deleteAssetsByPrefix($slug);
            $adminApi->deleteFolder($slug);
            $post->forceDelete();
            return $this->postSuccess(null, 'Xoá thành công!');
        }
    }
}
