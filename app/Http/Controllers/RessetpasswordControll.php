<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RessetpasswordControll extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate random token
        $token = Str::random(60);

        // Remove any existing tokens for this email
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Store new token
        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        // Generate reset link
        $resetLink = url("/api/password/reset?token=$token&email=" . urlencode($request->email));

        // Send email using Blade template
        Mail::send('emails.reset_password', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email)
                    ->from('no-reply@medicalhub.com', 'MedicalHub')
                    ->subject('Password Reset');
        });

        return response()->json([
            'message' => 'Password reset link has been sent to your email.',
            'status'  => true,
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Check if token is valid within 60 minutes
        $resetRequest = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', Carbon::now()->subMinutes(60)) 
            ->first();

        if (!$resetRequest) {
            return response()->json(['message' => 'Invalid or expired reset token.', 'status' => false], 400);
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Remove token from database
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Password has been reset successfully.',
            'status'  => true,
        ], 200);
    }
}


// Route::post('/password/reset-link', [RessetpasswordControll::class, 'sendResetLink']);
// Route::post('/password/update', [RessetpasswordControll::class, 'updatePassword']);
// use App\Http\Controllers\RessetpasswordControll;
