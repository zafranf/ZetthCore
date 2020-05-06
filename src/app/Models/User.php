<?php

namespace ZetthCore\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use ZetthCore\Models\Scopes\SiteScope;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use Notifiable;
    use SoftDeletes;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function role_ids()
    {
        return $this->hasMany('ZetthCore\Models\RoleUser');
    }

    public function socmed()
    {
        return $this->morphMany('ZetthCore\Models\SocmedData', 'socmedable');
    }

    public function detail()
    {
        return $this->hasOne('ZetthCore\Models\UserDetail');
    }

    public function verify()
    {
        return $this->hasOne('ZetthCore\Models\UserVerification');
    }

    public function getIsAdminAttribute()
    {
        return $this->can('admin.dashboard.index');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value . \Str::slug(env('APP_KEY')));
    }

}
