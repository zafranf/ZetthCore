<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use SoftDeletes;

    public function photo()
    {
        return $this->hasOne('ZetthCore\Http\Models\AlbumDetail', 'album_id')->where('type', 'photo');
    }

    public function photos()
    {
        return $this->hasMany('ZetthCore\Http\Models\AlbumDetail', 'album_id')->where('type', 'photo');
    }
}
