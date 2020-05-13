<?php

namespace ZetthCore\Models;

class PasswordReset extends Base
{
    protected $primaryKey = 'email';
    protected $fillable = [
        'email', 'token', 'created_at',
    ];
    protected $dates = ['created_at'];
    public $timestamps = false;
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'email', 'email');
    }

    public function getEmailAttribute($value)
    {
        return _decrypt($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = _decrypt($value) ? $value : _encrypt($value);
    }
}
