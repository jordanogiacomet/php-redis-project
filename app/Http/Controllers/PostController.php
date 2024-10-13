<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use App\Models\Post;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): JsonResponse
    {
        try {
            $posts = $this->postService->getAllPostsWithCache();
            return response()->json($posts);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts'], 500);
        }
    }

    public function indexWithCache(): JsonResponse
    {
        try {
            $posts = $this->postService->getAllPostsWithCache();
            return response()->json($posts);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts'], 500);
        }
    }

    public function indexWithoutCache(): JsonResponse
    {
        try {
            $posts = $this->postService->getAllPostsWithoutCache();
            return response()->json($posts);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch posts'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $post = $this->postService->getPostById($id);
            return response()->json($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch post'], 500);
        }
    }

    public function store(CreatePostRequest $request): JsonResponse
    {
        try {
            $post = $this->postService->createPost($request);
            return response()->json($post, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create post'], 500);
        }
    }

    public function update(UpdatePostRequest $request, $id): JsonResponse
    {
        try {
            $post = $this->postService->updatePost($id, $request);
            return response()->json($post);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update post'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $this->authorize('delete', Post::findOrFail($id));
            $this->postService->deletePost($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete post'], 500);
        }
    }
}