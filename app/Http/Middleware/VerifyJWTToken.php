<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Facade\FlareClient\Http\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $accessToken = explode(' ', $request->header('Authorization'))[1];

        try {
            $decoded = JWT::decode($accessToken, new Key(env('APP_ACCESS_TOKEN'), 'HS256'));

            request()->username = $decoded->nama;

            return next($request);
        } catch (Exception $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $th->getMessage()
                ],
                403
            );
        }
    }
}
