<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $dates = ['active_at'];
    public $appends = ['lang'];

    public function socmed()
    {
        return $this->morphMany('ZetthCore\Models\SocmedData', 'socmedable');
    }

    public function template()
    {
        return $this->belongsTo('ZetthCore\Models\Template');
    }

    public function getLangAttribute()
    {
        return explode("_", $this->language ?? 'id_ID')[0];
    }
}
