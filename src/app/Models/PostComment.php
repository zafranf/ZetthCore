<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    public function post()
    {
        return $this->belongsTo('ZetthCore\Models\Post');
    }

    public function approval()
    {
        return $this->belongsTo('ZetthCore\Models\User', 'approved_by');
    }

    public function subcomments()
    {
        return $this->hasMany('ZetthCore\Models\PostComment', 'parent_id', 'id')->where('status', 1)->with('subcomment');
    }
}
