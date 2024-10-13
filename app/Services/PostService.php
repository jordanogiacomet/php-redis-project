<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Support\Facades\Cache;

class PostService
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAllPostsWithCache()
    {
        return Cache::remember('posts', 60, function () {
            return $this->postRepository->all();
        });
    }

    public function getAllPostsWithoutCache()
    {
        return $this->postRepository->all();
    }

    public function getPostById($id)
    {
        return $this->postRepository->find($id);
    }

    public function createPost(CreatePostRequest $request)
    {
        $post = $this->postRepository->create($request);
        Cache::forget('posts'); // Invalida o cache
        return $post;
    }

    public function updatePost($id, UpdatePostRequest $request)
    {
        $post = $this->postRepository->update($id, $request);
        Cache::forget('posts'); // Invalida o cache
        return $post;
    }

    public function deletePost($id)
    {
        $result = $this->postRepository->delete($id);
        Cache::forget('posts'); // Invalida o cache
        return $result;
    }
}