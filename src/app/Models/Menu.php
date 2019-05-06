<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    public function submenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'parent_id', 'id')->where('status', 1)->orderBy('order')->with('submenu');
    }

    public function allSubmenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'parent_id', 'id')->orderBy('order')->with('allSubmenu');
    }

    public function group()
    {
        return $this->belongsTo('ZetthCore\Models\MenuGroup');
    }
}
