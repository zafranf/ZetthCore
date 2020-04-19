<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Model
{
    use SoftDeletes;

    public function menu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'group_id')->whereNull('parent_id')->orderBy('order');
    }

    public function allMenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'group_id')->whereNull('parent_id')->orderBy('order');
    }
}
