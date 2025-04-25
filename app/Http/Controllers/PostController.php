<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $role = $request->query('role'); // جيب role من query params (human أو vet)
        $query = Post::with(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'sections', 'comments']);

        if ($role && in_array($role, ['human', 'vet'])) {
            $query->where('role', $role);
        }

        $posts = $query->get();
        return response()->json(['data' => $posts], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $doctorId = Auth::id();
        if (!$doctorId) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $doctor = Auth::user(); // جيب الدكتور من الـ token
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.content' => 'nullable|string',
            'sections.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = Post::create([
            'doctor_id' => $doctorId,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'role' => $doctor->role, // ضيف role بناءً على role الدكتور
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
            $post->save();
        }

        foreach ($validated['sections'] ?? [] as $index => $section) {
            $sectionData = [
                'post_id' => $post->id,
                'title' => $section['title'] ?? '',
                'content' => $section['content'] ?? '',
            ];
            if ($request->hasFile("sections.$index.image")) {
                $sectionData['image'] = $request->file("sections.$index.image")->store('sections', 'public');
            }
            $post->sections()->create($sectionData);
        }

        $post->load(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'sections']);

        return response()->json(['data' => $post], 201);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'sections', 'comments']);
        return response()->json(['data' => $post], 200);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        if ($post->doctor_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $doctor = Auth::user();
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.content' => 'nullable|string',
            'sections.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post->update([
            'title' => $validated['title'] ?? $post->title,
            'content' => $validated['content'] ?? $post->content,
            'role' => $doctor->role, // تحديث role بناءً على role الدكتور
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('posts', 'public');
            $post->image = $path;
            $post->save();
        }

        if (isset($validated['sections'])) {
            $post->sections()->delete();
            foreach ($validated['sections'] as $index => $section) {
                $sectionData = [
                    'post_id' => $post->id,
                    'title' => $section['title'] ?? '',
                    'content' => $section['content'] ?? '',
                ];
                if ($request->hasFile("sections.$index.image")) {
                    $sectionData['image'] = $request->file("sections.$index.image")->store('sections', 'public');
                }
                $post->sections()->create($sectionData);
            }
        }

        $post->load(['doctor' => function ($query) {
            $query->select('id', 'name', 'image');
        }, 'sections']);

        return response()->json(['data' => $post], 200);
    }

    public function destroy(Post $post): JsonResponse
    {
        if ($post->doctor_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        foreach ($post->sections as $section) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}