<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use SoftDeletes;

    public function scopeActive($query)
    {
        return $query->where('status', 1);
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
