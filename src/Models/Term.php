<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use SoftDeletes;

    public function subcategory()
    {
        return $this->hasMany('ZetthCore\Models\Term', 'parent_id', 'id')->where('status', 1)->where('type', 'category')->with('subcategory');
    }

    public function allSubcategory()
    {
        return $this->hasMany('ZetthCore\Models\Term', 'parent_id', 'id')->where('type', 'category')->with('allSubcategory');
    }
}
