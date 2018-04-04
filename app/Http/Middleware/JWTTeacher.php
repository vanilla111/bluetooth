<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class JWTTeacher
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
//        if (! $user = Auth::guard('teachers')->authenticate()) {
//            return response()->json([
//                'status' => 404,
//                'message' => 'user_not_found'
//            ], 404);
//        }


        try {
            if (!$user = Auth::guard('teachers')->authenticate())
                return response()->json([
                    'status' => 404,
                    'message' => 'user_not_found'
                ], 404);
        } catch (AuthenticationException $e) {

            return response()->json([
                'status' => 404,
                'message' => 'user_authenticated_error'
            ], 404);
        }

        $request->attributes->add(compact('user'));

        return $next($request);
    }
}
