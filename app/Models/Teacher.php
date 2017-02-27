<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

class Teacher extends Model implements AuthenticatableContract, AuthorizableContract,  AuthenticatableUserContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'teachers';

    protected $primaryKey = 'tid';

    protected $fillable = ['trid', 'tName', 'password', 'tAcad', 'AcadName', 'tMajor', 'tPosition', 'remember_token'];

    protected $hidden = ['password', 'remember_token'];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Eloquent model method
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getStatistics($data, $num, $status)
    {
        $new_data = [];
        $m = count($data) / $num;
        for ($i = 0; $i < $num; $i++) {
            $new_data[$i]['stuNum'] = $data[$i]['stuNum'];
            $new_data[$i]['stuName'] = $data[$i]['stuName'];
            $new_data[$i]['week'] = [];
            $new_data[$i]['hash_day'] = [];
            $new_data[$i]['hash_lesson'] = [];
            $new_data[$i]['status'] = [];
            for ($j = 0; $j < $m; $j++) {
                $n = $i + $j * $num;
                array_push($new_data[$i]['week'], $data[$n]['week']);
                array_push($new_data[$i]['hash_day'], $data[$n]['hash_day']);
                array_push($new_data[$i]['hash_lesson'], $data[$n]['hash_lesson']);
                array_push($new_data[$i]['status'], $data[$n]['status']);
            }
        }

        $new_data = array_values($new_data);

        if ($status == 0)
            return $new_data;

        foreach ($new_data as $key => $value) {
            if (!in_array($status, $value['status']))
                unset($new_data[$key]);
            else {
                foreach ($value['status'] as $k => $v) {
                    if ($v != $status) {
                        unset($new_data[$key]['week'][$k]);
                        unset($new_data[$key]['hash_day'][$k]);
                        unset($new_data[$key]['hash_lesson'][$k]);
                        unset($new_data[$key]['status'][$k]);
                    }
                }
                $new_data[$key]['week'] = array_values($new_data[$key]['week']);
                $new_data[$key]['hash_day'] = array_values($new_data[$key]['hash_day']);
                $new_data[$key]['hash_lesson'] = array_values($new_data[$key]['hash_lesson']);
                $new_data[$key]['status'] = array_values($new_data[$key]['status']);
            }
        }

        return array_values($new_data);
    }

}
