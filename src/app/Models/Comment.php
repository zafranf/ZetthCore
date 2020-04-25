<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    public function post()
    {
        return $this->belongsTo('ZetthCore\Models\Post', 'commentable_id');
    }

    public function commentator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function approval()
    {
        return $this->belongsTo('App\Models\User', 'approved_by');
    }

    public function subcomments()
    {
        return $this->hasMany('ZetthCore\Models\Comment', 'parent_id', 'id')->where('status', 'active')->with(['subcomments', 'commentator']);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
