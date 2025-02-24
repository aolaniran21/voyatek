<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Blog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //
        $blogs = Blog::with(['posts', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate results

        return response()->json([
            'message' => 'Blogs retrieved successfully',
            'blogs' => $blogs
        ]);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create blog linked to authenticated user
        $blog = Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(), // Ensure user is authenticated
        ]);

        // Return JSON response
        return response()->json([
            'message' => 'Blog created successfully',
            'blog' => $blog
        ], 201);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }




    /**
     * Display the specified blog and its posts.
     */
    public function show(int $id)
    {
        $blog = Blog::with('posts')->findOrFail($id);
        return response()->json([
            'message' => 'Blog retrieved successfully',
            'blog' => $blog
        ]);
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, int $id)
    {
        $blog = Blog::findOrFail($id);

        // Authorization check - Ensure the authenticated user owns the blog
        if ($blog->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate request
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
        ]);

        // Update blog
        $blog->update($request->only(['title', 'description']));

        return response()->json([
            'message' => 'Blog updated successfully',
            'blog' => $blog
        ]);
    }

    /**
     * Remove the specified blog.
     */
    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        // Authorization check
        if ($blog->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $blog->delete();

        return response()->json(['message' => 'Blog deleted successfully']);
    }
}
