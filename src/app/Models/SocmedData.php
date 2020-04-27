<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SocmedData extends Base
{
    use SoftDeletes;

    public function socmed()
    {
        return $this->belongsTo('ZetthCore\Models\Socmed');
    }
}
