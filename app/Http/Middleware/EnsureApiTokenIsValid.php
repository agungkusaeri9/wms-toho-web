<?php

namespace App\Http\Middleware;

use App\Http\Controllers\API\V1\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class EnsureApiTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Cek apakah token valid
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return ResponseFormatter::error([], 'Token is invalid', 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return ResponseFormatter::error([], 'Token is expired', 401);
            } else {
                return ResponseFormatter::error([], 'Authorization Token not found', 401);
            }
        }

        return $next($request);
    }
}
