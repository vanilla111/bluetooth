<?php

namespace App\Http\Controllers\Zhihu;

use App\Models\Zhihu\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $guard = 'zhihu_user';

    public function toRegister(Request $request)
    {
        $user_info = $request->only('username', 'password');

        if (empty($user_info['username']) || empty($user_info['password']))
            return response()->json([
                'status' => 401,
                'info' => '用户名与密码不能为空'
            ], 401);

        //查询数据库中是否已有同名用户
        $user_m = new User();
        $res = $user_m->where(['username' => $user_info['username']])->first();
        if (!empty($res))
            return response()->json([
                'status' => 401,
                'info' => '用户名已被使用',
            ], 401);

        $data = [
            'username' => $user_info['username'],
            'password' => Hash::make($user_info['password'])
        ];
        if(!$user_m->create($data))
            return response()->json([
                'status' => 500,
                'info' => '未知错误'
            ], 500);

        $token = Auth::guard($this->getGuard())->attempt($user_info);

        return response()->json([
            'status' => 200,
            'info' => 'success',
            'data' => [
                'username' => $user_info['username'],
                'avatar' => NULL,
                'token' => $token
            ]
        ], 200);

    }

    public function toLogin(Request $request)
    {
        $credentials = $request->only('username','password');

        if (empty($credentials['username']) || empty($credentials['password']))
            return response()->json([
                'status' => 401,
                'info' => '用户名与密码不能为空',
                'data' => NULL
            ], 401);

        if ( $token = Auth::guard($this->getGuard())->attempt($credentials)) {
            return response()->json([
                'status' => 200,
                'info' => 'success',
                'data' => [
                    'username' => $credentials['username'],
                    'avatar' => NULL,
                    'token' => $token
                ]
            ], 200);
        } else {
            return response()->json([
                'status' => 401,
                'info' => 'failed',
                'data' => NULL
            ], 401);
        }
    }

    public function changePassword(Request $request)
    {
        //
    }
}
