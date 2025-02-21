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
                    'user' => auth()->user(),
                    'role' => auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Pilot') || auth()->user()->hasRole('Tenant')
                ]
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
            'data' => null
        ]);
    }
}
