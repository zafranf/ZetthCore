<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Base
{
    use SoftDeletes;

    protected $guarded = [];
}
