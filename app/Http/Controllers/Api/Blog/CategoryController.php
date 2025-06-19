<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(BlogCategory::all());
    }

    public function show($id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Категорію не знайдено'], 404);
        }
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug',
            'parent_id' => 'nullable|exists:blog_categories,id',
            'description' => 'nullable|string',
        ]);
        $category = BlogCategory::create($validated);
        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Категорію не знайдено'], 404);
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_categories,slug,' . $id,
            'parent_id' => 'nullable|exists:blog_categories,id',
            'description' => 'nullable|string',
        ]);
        $category->update($validated);
        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = BlogCategory::find($id);
        if (!$category) {
            return response()->json(['message' => 'Категорію не знайдено'], 404);
        }
        $category->delete();
        return response()->json(['message' => 'Категорію видалено']);
    }
}
