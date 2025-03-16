<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        //
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
