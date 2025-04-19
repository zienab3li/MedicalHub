<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\UserLoginRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function updateStatus(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'status' => 'required|in:active,archived',
    ]);

    $user->status = $request->status;
    $user->save();

    return response()->json([
        'message' => "status has been updated",
        'user' => $user
    ]);
}

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

        event(new UserRegistered($user));

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function updateUser(UpdateRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validated();
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


    // public function login(UserLoginRequest $request): JsonResponse
    // {
    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json([
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'user' => $user,
    //         'token' => $token,
    //     ]);
    // }


    public function login(UserLoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    
        $user = Auth::user();
    
        if ($user->status !== 'active') {
            Auth::logout();
    
            return response()->json([
                'message' => "acount not active"
            ], 403);
        }
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'image' => $user->image ? asset('storage/' . $user->image) : null,
        ];
    
        return response()->json([
            'message' => 'Login successful',
            'user' => $userData,
            'token' => $token,
        ]);
    }
    
    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }

    public function users(){
        $users = User::all();
        return response()->json([
            'message' => 'users',
            'users'   => $users
        ], 200);

    }
}
