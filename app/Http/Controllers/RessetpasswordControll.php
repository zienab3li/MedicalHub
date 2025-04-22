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

        $token = Str::random(60);
        DB::table('password_resets')->where('email', $request->email)->delete();

        DB::table('password_resets')->insert([
            'email'      => $request->email,
            'token'      => $token,
            'created_at' => Carbon::now(),
        ]);

        $frontendUrl = config('app.frontend_url');
        $resetLink = "$frontendUrl/reset-password?token=$token&email=" . urlencode($request->email);


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

    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'token'    => 'required',
    //         'email'    => 'required|email|exists:users,email',
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     // Mail::raw("Click the following link to reset your password: $resetLink", function ($message) use ($request) {
    //     //     $message->to($request->email)
    //     //             ->from('no-reply@medicalhub.com', 'MedicalHub') 
    //     //             ->subject('Password Reset');
    //     // });

    //     $resetRequest = DB::table('password_resets')
    //         ->where('email', $request->email)
    //         ->where('token', $request->token)
    //         ->where('created_at', '>=', Carbon::now()->subMinutes(30))
    //         ->first();

    //     if (!$resetRequest) {
    //         return response()->json(['message' => 'Invalid or expired reset token.', 'status' => false], 400);
    //     }

    //     User::where('email', $request->email)->update([
    //         'password' => Hash::make($request->password),
    //     ]);

    //     DB::table('password_resets')->where('email', $request->email)->delete();

    //     return response()->json([
    //         'message' => 'Password has been reset successfully.',
    //         'status'  => true,
    //     ], 200);
    // }

    public function updatePassword(Request $request)
{
    $request->validate([
        'token'    => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $resetRequest = DB::table('password_resets')
        ->where('token', $request->token)
        ->where('created_at', '>=', Carbon::now()->subMinutes(30))
        ->first();

    if (!$resetRequest) {
        return response()->json(['message' => 'Invalid or expired reset token.', 'status' => false], 400);
    }

    User::where('email', $resetRequest->email)->update([
        'password' => Hash::make($request->password),
    ]);

    DB::table('password_resets')->where('email', $resetRequest->email)->delete();

    return response()->json([
        'message' => 'Password has been reset successfully.',
        'status'  => true,
    ], 200);
}

}
