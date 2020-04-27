<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MenuGroup extends Base
{
    use SoftDeletes;

    public function menu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'group_id')->whereNull('parent_id')->active()->orderBy('order');
    }

    public function allMenu()
    {
        return $this->hasMany('ZetthCore\Models\Menu', 'group_id')->whereNull('parent_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
