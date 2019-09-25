<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use SoftDeletes;

    public function cover()
    {
        return $this->hasOne('ZetthCore\Models\AlbumDetail', 'album_id')->select('id', 'album_id', 'file');
    }

    public function photo()
    {
        return $this->hasOne('ZetthCore\Models\AlbumDetail', 'album_id');
    }

    public function photos()
    {
        return $this->hasMany('ZetthCore\Models\AlbumDetail', 'album_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
