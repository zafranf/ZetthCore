<?php

namespace ZetthCore\Models;

class UserDetail extends Base
{
    protected $primaryKey = 'user_id';
    protected $guarded = [];
    public $incrementing = false;
    public $appends = ['lang'];

    public function getLangAttribute()
    {
        return explode("_", $this->language ?? 'id_ID')[0];
    }
}
