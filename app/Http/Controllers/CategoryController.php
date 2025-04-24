<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAllCategories()
{
        $categories = Category::all();

        return response()->json([
            'status' => 200,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
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
        // add category => related to admin
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'type' => 'required|in:human,vet',

        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');  // Store image in storage/app/public/categories
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'type' => $request->type,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Category created successfully',
            'data' => $category
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'status' => 200,
            'data' => $category
        ]);
    }
    public function getCategoriesByType($type)
{
    if (!in_array($type, ['human', 'vet'])) {
        return response()->json([
            'status' => 400,
            'message' => 'Invalid category type'
        ]);
    }

    $categories = Category::where('type', $type)->get();

    return response()->json([
        'status' => 200,
        'message' => 'Categories retrieved successfully',
        'data' => $categories
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('categories', 'public');
            $category->image = $imagePath;
        }

        // Update category details
        $category->update([
            'name' => $request->name ?? $category->name ,
            'description' => $request->description ?? $category->description,
            'image' => $category->image,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Delete category
        $category->delete();
        // Delete category image if it exists
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Category deleted successfully',
        ]);
    }
}
