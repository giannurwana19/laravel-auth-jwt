<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ]);
        };

        $formData = $validator->validated();

        if (Auth::attempt($formData)) {
            $access_token = JWT::encode([
                'nama' => 'Gian Nurwana',
                'email' => 'gian@gmail.com',
                'iat' => time(),
                'exp' => time() + 15 // 15 detik
            ], env('APP_ACCESS_TOKEN'), 'HS256');

            $refresh_token = JWT::encode([
                'nama' => 'Gian Nurwana',
                'email' => 'gian@gmail.com',
                'iat' => time(),
                'exp' => time() + 20 // 20 detik
            ], env('APP_REFRESH_TOKEN'), 'HS256');

            setcookie('refreshToken', $refresh_token, time() + 20, '', '', false, true);

            return response()->json([
                'success' => true,
                'user' => Auth::user(),
                'access_token' => $access_token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah!',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        setcookie('refreshToken', '', -1);

        return response()->json([
            'success' => true,
            'message' => 'berhasil logout',
            'user' => Auth::user()
        ]);
    }
}
