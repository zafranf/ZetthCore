<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class TermData extends Model
{
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo('ZetthCore\Models\Post');
    }

    public function term()
    {
        return $this->belongsTo('ZetthCore\Models\Term');
    }
}
