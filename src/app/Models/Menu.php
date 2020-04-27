<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Base
{
    use SoftDeletes;

    public function submenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'parent_id', 'id')->where('status', 'active')->orderBy('order')->with('submenu')->orderBy('order');
    }

    public function allSubmenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'parent_id', 'id')->orderBy('order')->with('allSubmenu')->orderBy('order');
    }

    public function group()
    {
        return $this->belongsTo('ZetthCore\Models\MenuGroup');
    }
}
