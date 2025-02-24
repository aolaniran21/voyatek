<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }




    /**
     * Display a listing of posts under a specific blog.
     */
    public function index($blogId)
    {
        // Fetch all posts under the blog with likes & comments, paginated
        $posts = Post::where('blog_id', $blogId)
            ->with(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'posts' => $posts
        ]);
    }

    /**
     * Store a newly created post under a specific blog.
     */
    public function store(Request $request, $blogId)
    {
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Ensure the blog exists
        $blog = Blog::findOrFail($blogId);

        // Authorization check: Only blog owner can create posts
        if ($blog->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Create post under the blog
        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'blog_id' => $blogId,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ], 201);
    }

    /**
     * Display a single post with likes & comments.
     */
    public function show(string $id)
    {
        $post = Post::with(['likes', 'comments'])->findOrFail($id);

        return response()->json([
            'message' => 'Post retrieved successfully',
            'post' => $post
        ]);
    }

    /**
     * Update a post.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        // Authorization check: Only post owner can update
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate request
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
        ]);

        $post->update($request->only(['title', 'content']));

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ]);
    }

    /**
     * Remove the specified post.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        // Authorization check: Only post owner can delete
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
