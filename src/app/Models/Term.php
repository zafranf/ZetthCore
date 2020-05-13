<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Base
{
    use SoftDeletes;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function subcategory()
    {
        return $this->hasMany('ZetthCore\Models\Term', 'parent_id', 'id')->where('status', 'active')->where('type', 'category')->with('subcategory');
    }

    public function subcategory_all()
    {
        return $this->hasMany('ZetthCore\Models\Term', 'parent_id', 'id')->where('type', 'category')->with('subcategory_all');
    }
}
