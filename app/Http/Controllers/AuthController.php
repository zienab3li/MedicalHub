<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function updateUser(Request $request) {
        $user = $request->user();
    
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6|confirmed',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }
    
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
    
        $user->update($data);
    
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    }


    public function login(UserLoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);

        
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}

