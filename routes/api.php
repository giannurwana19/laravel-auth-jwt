<?php

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('get-token', function () {
    $access_token = JWT::encode([
        'nama' => 'Gian Nurwana',
        'email' => 'gian@gmail.com',
        'exp' => time() + 15 // 15 detik
    ], env('APP_ACCESS_TOKEN'), 'HS256');

    $refresh_token = JWT::encode([
        'nama' => 'Gian Nurwana',
        'email' => 'gian@gmail.com',
        'exp' => time() + 20 // 20 detik
    ], env('APP_REFRESH_TOKEN'), 'HS256');

    setcookie('refreshToken', $refresh_token, time() + 20, '', '', false, true);

    return response()->json([
        'access_token' => $access_token
    ]);
});
