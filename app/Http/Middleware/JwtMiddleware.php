<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            if ($request->is('api/users/login')) {
                return $next($request);
            }

            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token en lista negra'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        return $next($request);
    }
}
