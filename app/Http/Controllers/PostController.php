<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $role = $request->query('role');
        $query = Post::with(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'sections', 'comments']);

        if ($role && in_array($role, ['human', 'vet'])) {
            $query->where('role', $role);
        }

        $posts = $query->latest()->get();
        return response()->json(['data' => $posts], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $doctorId = Auth::id();
        if (!$doctorId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'inline_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'The image may not be greater than 5MB.',
            'inline_images.*.image' => 'Each inline file must be an image.',
            'inline_images.*.mimes' => 'Each inline image must be a file of type: jpeg, png, jpg, gif, webp.',
            'inline_images.*.max' => 'Each inline image may not be greater than 5MB.',
        ]);

        $content = $this->processInlineImages($validated['content'], $request->file('inline_images') ?? []);

        $post = Post::create([
            'doctor_id' => $doctorId,
            'title' => $validated['title'],
            'content' => $content,
            'role' => Auth::user()->role,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
            $post->save();
        }

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post->load('doctor')
        ], 201);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'comments']);
        return response()->json(['data' => $post], 200);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        if ($post->doctor_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'inline_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $content = isset($validated['content']) 
            ? $this->processInlineImages($validated['content'], $request->file('inline_images') ?? []) 
            : $post->content;

        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'content' => $content,
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
            $post->save();
        }

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post->load('doctor')
        ], 200);
    }

    public function destroy(Post $post): JsonResponse
    {
        if ($post->doctor_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $this->deleteInlineImages($post->content);
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    private function processInlineImages(string $content, array $inlineImages): string
    {
        foreach ($inlineImages as $image) {
            $filename = $image->getClientOriginalName();
            $path = $image->store('posts/inline', 'public');
            $content = str_replace(
                "placeholder_{$filename}",
                asset("storage/{$path}"),
                $content
            );
        }

        return $content;
    }

    private function deleteInlineImages(string $content): void
    {
        preg_match_all('/<img[^>]+src=["\'](.*?\/storage\/posts\/inline\/[^"\']+)["\']/i', $content, $matches);
        
        foreach ($matches[1] as $src) {
            $path = str_replace(asset('storage/'), '', $src);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}