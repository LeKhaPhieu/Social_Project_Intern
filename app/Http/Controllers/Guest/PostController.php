<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Service\Admin\CategoryService;
use App\Service\Guest\PostService;
use App\Service\User\CommentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    protected PostService $postService;

    protected CategoryService $categoryService;

    protected CommentService $commentService;

    public function __construct(
        PostService $postService,
        CategoryService $categoryService,
        CommentService $commentService,
    ) {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
        $this->commentService = $commentService;
    }

    public function index(Request $request): View
    {
        $data = $request->all();
        $posts = $this->postService->index($data);
        $categories = $this->categoryService->getAll();
        return view('guest.home')->with([
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    public function detail(Post $post): View
    {
        $categories = $this->categoryService->getAll();
        $relatedPosts = $this->postService->detailRelated($post->user_id, $post->id);
        $popularPosts = $this->postService->detailPopular($post->user_id, $post->id);
        $comments = $this->commentService->index($post->id);
        return view('guest.detail')->with([
            'post' => $post,
            'categories' => $categories,
            'relatedPosts' => $relatedPosts,
            'popularPosts' => $popularPosts,
            'comments' => $comments,
        ]);
    }
}
