<?php

namespace ZetthCore\Models;

class UserDetail extends Base
{
    protected $primaryKey = 'user_id';
    protected $guarded = [];
    public $incrementing = false;
}
