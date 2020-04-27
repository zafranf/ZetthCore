<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Base
{
    use SoftDeletes;

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
