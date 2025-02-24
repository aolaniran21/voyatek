<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
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

    public function likePost(Request $request, $postId)
    {
        // Ensure the post exists
        $post = Post::findOrFail($postId);

        // Get the authenticated user
        $userId = Auth::id();

        // Check if the user already liked the post
        $existingLike = Like::where('post_id', $postId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            return response()->json(['message' => 'You have already liked this post'], 409);
        }

        // Create a new like
        Like::create([
            'post_id' => $postId,
            'user_id' => $userId
        ]);

        return response()->json(['message' => 'Post liked successfully']);
    }
}
