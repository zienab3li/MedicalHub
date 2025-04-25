<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // Store feedback
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'nullable|exists:doctors,id',
            'type' => 'required|in:doctor,website',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user_id, // Replace with auth()->id() when using auth
            'doctor_id' => $validated['doctor_id'] ?? null,
            'type' => $validated['type'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully.',
            'data' => $feedback
        ], 201);
    }

    // Get all feedback (with optional filters)
    public function index(Request $request)
    {
        $query = Feedback::query();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $feedback = $query->with(['user', 'doctor'])->latest()->paginate(10);

        return response()->json([
            'message' => 'Feedback list retrieved.',
            'data' => $feedback
        ]);
    }

    // Not implemented: create form
    public function create()
    {
        return response()->json(['message' => 'Not implemented.'], 200);
    }

    // Show single feedback
    public function show(Feedback $feedback)
    {
        return response()->json([
            'message' => 'Feedback retrieved.',
            'data' => $feedback->load(['user', 'doctor'])
        ]);
    }

    // Not implemented: edit form
    public function edit(Feedback $feedback)
    {
        return response()->json(['message' => 'Not implemented.'], 200);
    }

    // Update feedback (optional for admins)
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $feedback->update($validated);

        return response()->json([
            'message' => 'Feedback updated.',
            'data' => $feedback
        ]);
    }

    // Delete feedback (optional for admins)
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return response()->json([
            'message' => 'Feedback deleted.'
        ]);
    }
}