<?php

namespace App\Http\Middleware\Teacher;

use Closure;

class Statistics
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
        $allow = ['major', 'grade', 'scNum', 'jxbID'];
        $info = $request->only($allow);

        if (!empty($info['major'])) {
            if ($info['major'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'major为非负整数'
                ], 400);
        } else
            $info['major'] = 0;

        if (!empty($info['grade'])) {
            if ($info['grade'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'grade为非负整数'
                ], 400);
        } else
            $info['grade'] = 0;

        if (!empty($info['scNum'])) {
            if ($info['scNum'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'scNum为非负整数'
                ], 400);
        } else
            $info['scNum'] = 0;

        if (empty($info['jxbID']))
            $info['jxbID'] = 0;

        $info['year'] = getCourseYear();
        $info['week'] = getNowWeek();

        $request->attributes->add(compact('info'));

        return $next($request);
    }
}
