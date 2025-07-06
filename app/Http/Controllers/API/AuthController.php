<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data=$request->validate([
            'firstName'     => 'required|string|max:255',
            'lastName'     => 'required|string|max:255',
            'phone'            => 'required|string|max:15',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create(
           $data
        );

        $token = $user->createToken('auth_token');

        return [
            'token' => $token->plainTextToken,
            'user'  => $user,
        ];
    }
    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email|exists:users,email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return [
            'message' => 'Invalid credentials',
        ];
    }

    $token = $user->createToken('auth_token');

    return [
        'token' => $token->plainTextToken,
        'user'  => $user,
    ];
}

public function logout(Request $request)
{

    $request->user()->tokens()->delete();

    return [
        'message' => 'Logged out successfully',
    ];
}
}
