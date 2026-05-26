<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Admin extends Authenticatable implements JWTSubject
{
//    use BelongsToCompany;

    protected $fillable = [
        'id',
        'super_admin',
        'name',
        'email',
        'phone',
        'image',
        'role_id',
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
        'company_id',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];
    protected $hidden = [
        'password',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id');
    }


    public function hasAbility($permissions) : bool
    {
        $role = $this->role;

        if (!$role){
            return false;
        }

        foreach ($role->permissions as $permission){
            if ( is_array($permissions) && in_array($permission, $permissions)){
                return true;
            } elseif (is_string($permissions) && strcmp($permissions, $permission) == 0){
                return true;
            }
        }
        return false;
    }



    /** JWT **/
    public function getJWTIdentifier()
    {
        return $this->getKey(); // لن يُستخدم هنا
    }

    public function getJWTCustomClaims()
    {
        return [];
    }



} //end of class
