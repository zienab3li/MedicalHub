<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Reply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $postId = $request->query('post_id');
        $query = Comment::with(['post', 'user', 'replies.user', 'replies.doctor'])
            ->orderBy('created_at', 'desc');

        if ($postId) {
            $query->where('post_id', $postId);
        }

        $comments = $query->get();
        return response()->json(['data' => $comments], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|min:1|max:1000',
        ]);

        if (!Auth::guard('api')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::guard('api')->id(),
            'comment' => $request->comment,
        ]);

        return response()->json(['data' => $comment->load(['post', 'user', 'replies.user', 'replies.doctor'])], 201);
    }

    public function show(Comment $comment): JsonResponse
    {
        return response()->json(['data' => $comment->load(['post', 'user', 'replies.user', 'replies.doctor'])], 200);
    }

    public function update(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'comment' => 'required|string|min:1|max:1000',
        ]);

        if (Auth::guard('api')->id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update(['comment' => $request->comment]);
        return response()->json(['data' => $comment->load(['post', 'user', 'replies.user', 'replies.doctor'])], 200);
    }

    public function destroy(Comment $comment): JsonResponse
    {
        if (Auth::guard('api')->id() !== $comment->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->replies()->delete();
        $comment->delete();
        return response()->json(['message' => 'Comment and its replies deleted successfully'], 200);
    }

    public function reply(Request $request, Comment $comment): JsonResponse
    {
        $request->validate([
            'reply' => 'required|string|min:1|max:1000',
        ]);

        if (Auth::guard('doctor')->check()) {
            $reply = Reply::create([
                'comment_id' => $comment->id,
                'doctor_id' => Auth::guard('doctor')->id(),
                'reply' => $request->reply,
            ]);
        } elseif (Auth::guard('api')->check()) {
            $reply = Reply::create([
                'comment_id' => $comment->id,
                'user_id' => Auth::guard('api')->id(),
                'reply' => $request->reply,
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['data' => $reply->load(['comment', 'user', 'doctor'])], 201);
    }

    public function updateReply(Request $request, Reply $reply): JsonResponse
    {
        $request->validate([
            'reply' => 'required|string|min:1|max:1000',
        ]);

        $userId = Auth::guard('api')->id();
        $doctorId = Auth::guard('doctor')->id();

        if ($reply->user_id !== $userId && $reply->doctor_id !== $doctorId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reply->update(['reply' => $request->reply]);
        return response()->json(['data' => $reply->load(['comment', 'user', 'doctor'])], 200);
    }

    public function destroyReply(Reply $reply): JsonResponse
    {
        $userId = Auth::guard('api')->id();
        $doctorId = Auth::guard('doctor')->id();

        if ($reply->user_id !== $userId && $reply->doctor_id !== $doctorId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reply->delete();
        return response()->json(['message' => 'Reply deleted successfully'], 200);
    }
}
?>