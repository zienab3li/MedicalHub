<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index():JsonResponse
    {
      $comments = Comment::with('post','user')->get();
      return response()->json(['data'=>$comments],200); 
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
        'post_id'=>'required|exists:posts,id',
        'user_id'=>'required|exists:users,id',
        'comment'=>'required|string',
    ]);
    $comment= Comment::create($request->all());
    return response()->json(['data'=>$comment],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment):JsonResponse
    {
        return response()->json(['data'=>$comment->load('post','user')],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment):JsonResponse
    {
        $request->validate([
            'comment'=>'sometimes|string',
        ]);
        $comment->update($request->all());
        return response()->json(['data'=>$comment],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment):JsonResponse
    {
        $comment->delete();
        return response()->json(['message'=>'comment deleted successfully'],200);
    }
}
