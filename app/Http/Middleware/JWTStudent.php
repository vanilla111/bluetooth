<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class JWTStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        if (! $user = Auth::guard('students')->authenticate()) {
//            return response()->json([
//                'status' => 404,
//                'message' => 'user_not_found'
//            ], 404);
//        }

        try {
            if (!$user = Auth::guard('students')->authenticate())
                return response()->json([
                    'status' => 404,
                    'message' => 'user_not_found'
                ], 404);
        } catch (AuthenticationException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'user_not_found'
            ], 404);
        }

        $request->attributes->add(compact('user'));

        return $next($request);
    }
}
