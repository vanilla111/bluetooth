<?php

namespace App\Http\Middleware\Teacher;

use Closure;

class Attendance
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
        $allow = ['jxbID', 'hash_day', 'hash_lesson', 'week', 'year', 'major', 'grade', 'class', 'scNum','status'];
        $info = $request->only($allow);

        if (empty($info['jxbID']))
            return response()->json([
                'status' => 400,
                'message' => 'jxbID参数必须'
            ], 400);

        if (!empty($info['week'])) {
            if ($info['week'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'week为非负整数'
                ], 400);
        } else
            $info['week'] = 0;

        if (!empty($info['hash_lesson'])) {
            if ($info['hash_lesson'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'hash_lesson参数为非负整数'
                ], 400);
        } else
            $info['hash_lesson'] = 0;

        if (!empty($info['hash_day'])) {
            if ($info['hash_day'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'hash_day参数为非负整数'
                ], 400);
        } else
            $info['hash_day'] = 0;

        if (!empty($info['year'])) {
            if ($info['year'] <= 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'year参数为整数'
                ], 400);
        } else
            $info['year'] = getCourseYear();

        if (!empty($info['status'])) {
            if ($info['status'] < 0 || $info['status'] >= 10)
                return response()->json([
                    'status' => 400,
                    'message' => 'status参数错误'
                ], 400);
        } else
            $info['status'] = 0;

        $request->attributes->add(compact('info'));

        return $next($request);
    }
}
