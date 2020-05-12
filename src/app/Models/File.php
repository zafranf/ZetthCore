<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Base
{
    use SoftDeletes;

    protected $guarded = [];

    public function albums()
    {
        return $this->morphedByMany('ZetthCore\Models\Album', 'fileable');
    }
}
