<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Inbox extends Base
{
    use SoftDeletes;

    public function getEmailAttribute($value)
    {
        return _decrypt($value);
    }

    public function getPhoneAttribute($value)
    {
        return _decrypt($value);
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = _decrypt($value) ? $value : _encrypt($value);
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = _decrypt($value) ? $value : _encrypt($value);
    }
}
