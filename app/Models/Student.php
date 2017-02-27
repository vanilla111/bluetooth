<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

class Student extends Model implements AuthenticatableContract, AuthorizableContract,  AuthenticatableUserContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'students';

    protected $primaryKey = 'sid';

    protected $fillable = ['stu_code', 'password', 'stuName', 'idNum','gender', 'stuAcad', 'stuMajor', 'stuGrade',
        'stuClass', 'imageUrl', 'remember_token'];

    protected $hidden = ['password', 'remember_token'];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Eloquent model method
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getStuInfo($condition, $need)
    {
        return $this->where($condition)->select($need)->first();
    }

    public function getStatistics($data)
    {
        $statistics = [];
        $statistics['leave']['num'] = 0;
        $statistics['absence']['num'] = 0;
        $statistics['late']['num'] = 0;
        $statistics['leave_early']['num'] = 0;
        $statistics['leave']['data'] = [];
        $statistics['absence']['data'] = [];
        $statistics['late']['data'] = [];
        $statistics['leave_early']['data'] = [];
        //1统计缺勤次数
        foreach ($data as $key => $value) {
            if ($value['status'] == env('LEAVE')) {
                $statistics['leave']['num']++;
                array_push($statistics['leave']['data'], $value);
            } elseif ($value['status'] == env('ABSENCE')) {
                $statistics['absence']['num']++;
                array_push($statistics['absence']['data'], $value);
            } elseif ($value['status'] == env('LATE')) {
                $statistics['late']['num']++;
                array_push($statistics['late']['data'], $value);
            } elseif ($value['status'] == env('LEAVE_EARLY')) {
                $statistics['leave_early']['num']++;
                array_push($statistics['leave_early']['data'], $value);
            }
        }

        return $statistics;
    }
}
