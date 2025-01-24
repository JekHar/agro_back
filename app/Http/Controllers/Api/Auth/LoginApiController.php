<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginApiController extends Controller
{
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'token' => auth()->user()->createToken('authToken')->plainTextToken,
                    'user' => auth()->user()
                ]
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}