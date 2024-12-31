<?php

namespace ZetthCore\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use ZetthCore\Models\Scopes\SiteScope;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions;
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

    protected $dateFormat = 'Y-m-d H:i:s.u';
    public $appends = ['lang'];

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
        return $this->isAbleTo('admin.dashboard.index');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getLangAttribute()
    {
        return explode("_", $this->language ?? 'id_ID')[0];
    }

    public function getEmailAttribute($value)
    {
        return _decrypt($value);
    }

    public function getNameAttribute($value)
    {
        return _decrypt($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = _decrypt($value) ? $value : _encrypt($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = _decrypt($value) ? $value : _encrypt($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value . \Str::slug(config('app.key')));
    }
}
