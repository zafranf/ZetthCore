<?php

namespace ZetthCore\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'fullname', 'email', 'password', 'is_first_login', 'status', 'verification_code', 'created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'token', 'token_expire', 'verification_code', 'verified_at',
    ];

    public function role_ids()
    {
        return $this->hasMany('ZetthCore\Models\RoleUser');
    }

    public function getIsAdminAttribute()
    {
        return $this->can('admin.dashboard.index');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /* public function roles()
{
return $this->belongsToMany('ZetthCore\Models\Role', 'role_user', 'role_id', 'user_id');
} */
}
