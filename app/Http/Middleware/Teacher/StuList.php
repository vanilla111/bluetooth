<?php

namespace App\Http\Middleware\Teacher;

use Closure;

class StuList
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
        $allow = ['stuNum', 'stuName',
            'year', 'month', 'week', 'major', 'grade', 'scNum','status',
            'today', //获取当天信息的参数 true or false
            'this_month', //获取当月信息的参数 true or false
            'page', 'per_page'];
        $info = $request->only($allow);

        $info['page'] = $info['page'] ? : 1;
        $info['per_page'] = $info['per_page'] ? : 20;

        if (!empty($info['major'])) {
            if ($info['major'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'major为非负整数'
                ], 400);
        }

        if (!empty($info['grade'])) {
            if ($info['grade'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'grade为非负整数'
                ], 400);
            elseif ($info['grade'] == 'NaN') {
                $info['grade'] = null;
            }
        }

        if (!empty($info['scNum'])) {
            if ($info['scNum'] < 0)
                return response()->json([
                    'status' => 400,
                    'message' => 'scNum为非负整数'
                ], 400);
            elseif ($info['scNum'] == 'undefined') {
                $info['scNum'] = null;
            }
        }

        if (!empty($info['week'])) {
            if ($info['week'] <= 0 ) {
                $info['week'] = getNowWeek();
            }
        }

        if ($info['today'] != 'true')
            $info['today'] = false;

        if ($info['this_month'] != 'true')
            $info['this_month'] = false;

        $info['year'] = getCourseYear();
        //return response()->json($info);

        $request->attributes->add(compact('info'));

        return $next($request);
    }
}
