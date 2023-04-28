<?php

use App\Http\Controllers\API\AuthController;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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


Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/logout', [AuthController::class, 'logout']);

Route::get('get-token', function () {
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
        'access_token' => $access_token
    ]);
});

Route::get('cek-token', function (Request $request) {
    try {
        $accessToken = $request->bearerToken();
        $decodedUser = JWT::decode($accessToken, new Key(env('APP_ACCESS_TOKEN'), 'HS256'));
        $user = User::find($decodedUser->id);

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    } catch (Exception $th) {
        return response()->json(
            [
                'success' => false,
                'message' => $th->getMessage()
            ],
            403
        );
    }
})->middleware('verifyTokenJWT');
