<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    // all products
    public function index()
    {
        $products = Product::all();
        return response([
            'status' => 200,
            'message' => 'Products List',
            'data' => $products
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
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    //  show product by category

    public function show($category_id)
    {
        $products = Product::where('category_id', $category_id)->get();
        if ($products->isEmpty()) {
            return response()->json([
                'status' => 404,
                'message' => 'No products found in this category',
                'data' => []
            ]);
        }

        return response([
            'status' => 200,
            'message' => 'Products List',
            'data' => $products
        ]);
    }

    public function showProduct($id)
    {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Product details',
            'data' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return response()->json([
            'status' => 200,
            'message' => 'Product details',
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'nullable|string|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,

        ]);
        return response()->json([
            'status' => 200,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }


public function showHumanProducts($type)
{
    // $products = Product::whereHas('category', function ($query) {
    //     $query->where('type', 'human');
    // })->with('category')->get();

    // if ($products->isEmpty()) {
    //     return response()->json([
    //         'status' => 404,
    //         'message' => 'No human products found',
    //         'data' => []
    //     ]);
    // }

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Human Products List',
    //     'data' => $products
    // ]);

    if (!in_array($type, ['human', 'vet'])) {
        return response()->json([
            'status' => 400,
            'message' => 'Invalid category type'
        ], 400);
    }

    $products = Product::whereHas('category', function ($query) use ($type) {
        $query->where('type', $type);
    })->get();

    return response()->json([
        'status' => 200,
        'message' => 'Products retrieved successfully',
        'data' => $products
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Product deleted successfully'
        ]);
    }
}
