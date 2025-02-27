<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function commentPost(Request $request, $postId)
    {
        // Validate request
        $request->validate(['content' => 'required|string|max:500']);

        // Ensure the post exists
        $post = Post::findOrFail($postId);

        // Get the authenticated user
        $userId = Auth::id();

        // Create the comment
        Comment::create([
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $request->content
        ]);

        return response()->json(['message' => 'Comment added successfully']);
    }
}
