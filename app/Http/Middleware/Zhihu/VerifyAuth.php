<?php

namespace App\Http\Middleware\Zhihu;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyAuth
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
        if (! $user = Auth::guard('zhihu_user')->authenticate()) {
            return response()->json([
                'status' => 404,
                'message' => 'user_not_found'], 404);
        }

        $request->attributes->add(compact('user'));

        return $next($request);
    }
}
