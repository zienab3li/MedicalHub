<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
        $posts = Post::with('doctor','comments')->get();
        return response()->json(['data'=> $posts],200);
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
    public function store(Request $request):JsonResponse
    {
        $request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'title'=>'required|string|max:255',
            'content'=>'required|string',
        ]);
        $post = Post::create($request->all());
        return response()->json(['data'=>$post],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post):JsonResponse
    {
    return response()->json(['data'=>$post->load('doctor','comments')],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post):JsonResponse
    {
        $request->validate([
            'title'=>'sometimes|string|max:255',
            'content'=>'sometimes|string'
        ]);
        $post->update($request->all());
        return response()->json(['data'=>$post],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post):JsonResponse
    {
        $post ->delete();
        return response()->json(['message'=>'post deleted successfully'],200);
    }
}
