<?php

namespace ZetthCore\Models;

class TermData extends Base
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
