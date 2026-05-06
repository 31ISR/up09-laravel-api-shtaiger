<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request-> validate([
            "name"=> 'required|string|max:255',
            "email"=> 'required|email|unique:users',
            "password"=> 'required|string|min:8|confirmed',
        ]);
        
        $user =  User::create([
            'name' => $data ['name'],
            'email' => $data ['email'],
            'password' => Hash::make($data ['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response ()-> json ([
            'user' => $user,
            'token' => $token,
            'token_type' => "Bearer"
        ],201);

    }
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            "email" => "email|required",
            "password" => "string|required"]);
        
            $user = User::where("email", $data["email"])->first();

            if (! $user || ! Hash::check($data["password"], $user->password)) {
                return response()->json([
                    "message" => "Неправильная почта или пароль"
                    ],401);

            }
            $token = $user->createToken('auth_token')->plainTextToken;

            return response ()-> json ([
                'user' => $user,
                'token' => $token,
                'token_type' => "Bearer"
            ],200);
            
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->cerrentAccessToken()->delete();

        return response()->json([
            "message"=> "Сессия закончена"
        ],200);
    }
}
