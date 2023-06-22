<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService
{
    public function login($credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return ['token' => $token];
        }

        return ['error' => 'Credenciais inválidas'];
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
    }
    public function register($data)
{
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    $token = $user->createToken('MyApp')->plainTextToken;
    return ['token' => $token];
}

public function getUser()
{
    return Auth::user();
}
}
