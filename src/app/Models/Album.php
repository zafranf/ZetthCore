<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Base
{
    use SoftDeletes;

    public function cover()
    {
        return $this->morphOne('ZetthCore\Models\File', 'fileable');
    }

    public function photo()
    {
        return $this->morphOne('ZetthCore\Models\File', 'fileable');
    }

    public function photos()
    {
        return $this->morphMany('ZetthCore\Models\File', 'fileable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
