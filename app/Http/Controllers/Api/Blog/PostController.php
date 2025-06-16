<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $posts = BlogPost::with(['user', 'category'])->paginate($perPage);
        return response()->json($posts);
    }
}
