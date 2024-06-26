<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    public function login(Request $request)
    {
        if (
            !Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {
            return response(['message' => 'الايميل او كلمه السر خاطئة'], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'user'=> $user ,
            'message' => "تم تسجيل الدخول بنجاح",
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ],200);
    }
    public function register(Request $request)
    {
        return $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

    }
    public function user()
    {
        return Auth::user();
    }
    public function logout()
    { 
        $user = Auth::user();
        $user->tokens()->delete();
        return response([
            'message' => "تم تسجل خروجك بنجاح"
        ], 200);
    }
}
