<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = Comment::with('post', 'user')->get();
        return response()->json(['data' => $comments], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'post_id' => 'required|exists:posts,id',
                'comment' => 'required|string',
            ]);

            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $comment = Comment::create([
                'post_id' => $request->post_id,
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);

            return response()->json(['data' => $comment->load('post', 'user')], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment): JsonResponse
    {
        return response()->json(['data' => $comment->load('post', 'user')], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        try {
            $request->validate([
                'comment' => 'sometimes|string',
            ]);

            if (Auth::id() !== $comment->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $comment->update($request->all());
            return response()->json(['data' => $comment->load('post', 'user')], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            if (Auth::id() !== $comment->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $comment->delete();
            return response()->json(['message' => 'Comment deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}