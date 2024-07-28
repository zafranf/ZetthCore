<?php

namespace ZetthCore\Models;

use Laratrust\Models\Permission as LaratrustPermission;

class Permission extends LaratrustPermission
{
    protected $dateFormat = 'Y-m-d H:i:s.u';
    protected $fillable = ['name', 'display_name', 'description'];
}
