<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if (!$status) {
            return response()->json([
                'message' => 'No se ha podido enviar el enlace de recuperación',
                'status' => 'error'
            ], 200);
        }


        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Se ha enviado el enlace de recuperación a tu correo',
                'status' => 'success'
            ]);
        }

        return response()->json([
            'message' =>  'Se ha enviado el enlace de recuperación a tu correo',
            'status' => 'success'
        ], 200);
    }
}
