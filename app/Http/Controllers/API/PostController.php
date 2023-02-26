<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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
        //Check if youtube_embed_code isset then choose this
        if(isset($post['youtube_embed_code'])) {
            $post['preview'] = $post['youtube_embed_code'];
            $post = Arr::except($post, ['youtube_embed_code']);
        }
        //Or else choose this
        else if ($post['preview']) {
            array_unshift($post['preview'],'');
            unset($post['preview'][0]);
            $count = count($post['preview']);
            $preview = '';
            //Lưu array files vào Cloudinary
            for ($i = 1; $i<=$count; $i++) {
                $result = $post['preview'][$i]->storeOnCloudinaryAs('posts/'.$post['slug'], $i);
                // $path = str_replace("https://res.cloudinary.com/saymetruyen/image/upload/", "", $result->getSecurePath());
                // $preview .= $path . ",";
                $preview .= $result->getFileName() . "." . $result->getExtension() . ",";
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

    public function update(UpdatePostRequest $request, $id)
    {
        $updatePost = $request->validated();
        $updatePost['slug'] = Str::slug($updatePost['name']);
        $updatePost['user_id'] = 1;
        if(isset($updatePost['youtube_embed_code'])) {
            $updatePost['preview'] = $updatePost['youtube_embed_code'];
            $updatePost = Arr::except($updatePost, ['youtube_embed_code']);
        } else if ($updatePost['preview']) {
            foreach($updatePost['preview'] as $key => $value)
            dd($value);
        }
        Post::where('id', $id)->update($updatePost);
        return $this->postSuccess($updatePost, 'Chỉnh sửa thành công!');
    }

    public function delete($id)
    {
        Post::where('id', $id)->delete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }

    public function destroy($id)
    {
        Post::where('id', $id)->forceDelete();
        return $this->postSuccess(null, 'Xoá thành công!');
    }
}
