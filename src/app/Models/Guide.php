<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Guide extends Base
{
    use SoftDeletes;

    public $appends = ['roles_array'];

    public function subguide()
    {
        return $this->hasMany('ZetthCore\Models\Guide', 'parent_id', 'id')->active()->orderBy('order')->with('subguide')->orderBy('order');
    }

    public function allSubguide()
    {
        return $this->hasMany('ZetthCore\Models\Guide', 'parent_id', 'id')->orderBy('order')->with('allSubguide')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getRolesArrayAttribute()
    {
        return explode(',', $this->roles);
    }
}
