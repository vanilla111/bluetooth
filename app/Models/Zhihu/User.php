<?php

namespace App\Models\zhihu;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract,  AuthenticatableUserContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'zh_person';

    protected $primaryKey = 'id';

    protected $fillable = ['username', 'password', 'avatar', 'token'];

    protected $hidden = ['password', 'token'];

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Eloquent model method
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
