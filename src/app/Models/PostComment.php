<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    public function post()
    {
        return $this->belongsTo('ZetthCore\Http\Models\Post');
    }

    public function approval()
    {
        return $this->belongsTo('ZetthCore\Http\Models\User', 'approved_by');
    }
}
