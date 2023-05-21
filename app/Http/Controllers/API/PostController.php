<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        return PostResource::collection(Post::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'website_id' => 'required|numeric'
        ]);

        $website = Website::findOrFail($validatedData['website_id']);

        $post = Post::create($validatedData);

        return new PostResource($post);
    }
}
