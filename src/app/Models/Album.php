<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Base
{
    use SoftDeletes;

    public function cover()
    {
        return ($this->cover_id ? $this->belongsTo('ZetthCore\Models\File', 'cover_id') : $this->photo())->select('file');
    }

    public function photo()
    {
        return $this->hasOneThrough(
            'ZetthCore\Models\File',
            'ZetthCore\Models\Fileable',
            'fileable_id',
            'id',
            'id',
            'file_id',
        );
    }

    public function photos()
    {
        return $this->morphToMany('ZetthCore\Models\File', 'fileable');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
