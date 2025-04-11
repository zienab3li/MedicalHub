<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Throwable;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    // public function callback($provider)
    // {


    //     try {
    //         $provider_user = Socialite::driver($provider)->user();
    //         $user = User::where([
    //             'provider' => $provider,
    //             'provider_id' => $provider_user->id
    //         ])->first();
            


    //         if (!$user) {
    //             $user = User::create([
    //                 'provider' => $provider,
    //                 'provider_id' => $provider_user->id,
    //                 'name' => $provider_user->name,
    //                 'email' => $provider_user->email,
    //                 'password' => Hash::make(Str::random(8)),
    //                 'provider_token' => $provider_user->token,

    //             ]);
    //         }


    //         Auth::login($user);
    //         // return 'login done';

    //         // return redirect()->route('home');


    //     } catch (Throwable $e) {
    //         return redirect()->route('user.login')->withErrors([
    //             'email' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function callback($provider)
{
    try {
        $provider_user = Socialite::driver($provider)->user();
        $user = User::where([
            'provider' => $provider,
            'provider_id' => $provider_user->id
        ])->first();

        if (!$user) {
            $user = User::create([
                'provider' => $provider,
                'provider_id' => $provider_user->id,
                'name' => $provider_user->name,
                'email' => $provider_user->email,
                'password' => Hash::make(Str::random(8)),
                'provider_token' => $provider_user->token,
            ]);
        }

        Auth::login($user);
        
        $token = $user->createToken('auth-token')->plainTextToken;
        
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'token' => $token
        ];
        
        return redirect()->away('http://localhost:4200?token=' . $token . '&user=' . urlencode(json_encode($userData)));

    } catch (Throwable $e) {
        return redirect()->away('http://localhost:4200/login?error=' . urlencode($e->getMessage()));
    }
}
}


