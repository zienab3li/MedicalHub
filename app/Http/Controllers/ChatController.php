<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\NewMessage;

class ChatController extends Controller
{
    public function startConversation(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id'
        ]);

        // Check if user has an appointment with the doctor
        $hasAppointment = Appointment::where('user_id', Auth::id())
            ->where('doctor_id', $request->doctor_id)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if (!$hasAppointment) {
            return response()->json([
                'message' => 'You must have an appointment with this doctor to start a chat'
            ], 403);
        }

        // Check if there's already an active conversation
        $conversation = Conversation::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'doctor_id' => $request->doctor_id,
            ],
            [
                'is_active' => true
            ]
        );

        return response()->json([
            'message' => 'Conversation started successfully',
            'data' => $conversation->load('user', 'doctor')
        ], 201);
    }

    public function getConversations()
    {
        $user = Auth::user();
        
        if ($user instanceof \App\Models\User) {
            // For patients
            $conversations = Conversation::where('user_id', $user->id)
                ->where('is_active', true)
                ->with(['doctor', 'latestMessage'])
                ->get();
        } else {
            // For doctors
            $conversations = Conversation::where('doctor_id', $user->id)
                ->where('is_active', true)
                ->with(['user', 'latestMessage'])
                ->get();
        }

        return response()->json([
            'data' => $conversations
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string'
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($user instanceof \App\Models\User) {
            if ($conversation->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to send messages in this conversation'
                ], 403);
            }
            $senderType = 'user';
        } else {
            if ($conversation->doctor_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to send messages in this conversation'
                ], 403);
            }
            $senderType = 'doctor';
        }

        // Check if conversation is active
        if (!$conversation->is_active) {
            return response()->json([
                'message' => 'This conversation is no longer active'
            ], 403);
        }

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message
        ]);

        // Broadcast the new message event
        broadcast(new NewMessage($message))->toOthers();

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message->load('sender', 'conversation')
        ], 201);
    }

    public function getMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($user instanceof \App\Models\User) {
            if ($conversation->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to view messages in this conversation'
                ], 403);
            }
        } else {
            if ($conversation->doctor_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to view messages in this conversation'
                ], 403);
            }
        }

        $messages = Message::where('conversation_id', $conversationId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'data' => $messages
        ]);
    }

    public function markAsRead($messageId)
    {
        $message = Message::findOrFail($messageId);
        $user = Auth::user();
        
        // Check if user is part of this conversation
        if ($user instanceof \App\Models\User) {
            if ($message->conversation->user_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }
        } else {
            if ($message->conversation->doctor_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }
        }

        $message->update(['is_read' => true]);
        return response()->json([
            'message' => 'Message marked as read'
        ]);
    }

    public function endConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $user = Auth::user();

        // Check if user is part of this conversation
        if ($user instanceof \App\Models\User) {
            if ($conversation->user_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to end this conversation'
                ], 403);
            }
        } else {
            if ($conversation->doctor_id !== $user->id) {
                return response()->json([
                    'message' => 'You are not authorized to end this conversation'
                ], 403);
            }
        }

        $conversation->update(['is_active' => false]);

        return response()->json([
            'message' => 'Conversation ended successfully'
        ]);
    }
} 