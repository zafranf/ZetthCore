<?php

namespace ZetthCore\Models;

class Language extends Base
{
    protected $primaryKey = 'name';
    protected $guarded = [];
    public $incrementing = false;
}
