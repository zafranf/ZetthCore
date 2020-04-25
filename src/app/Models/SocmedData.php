<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocmedData extends Model
{
    use SoftDeletes;

    public function socmed()
    {
        return $this->belongsTo('ZetthCore\Models\Socmed');
    }
}
