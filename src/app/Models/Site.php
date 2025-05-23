<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s.u';
    protected $casts = [
        'active_at' => 'datetime',
    ];
    public $appends = ['lang'];

    public function socmed()
    {
        return $this->morphMany('ZetthCore\Models\SocmedData', 'socmedable');
    }

    public function template()
    {
        return $this->morphOne('ZetthCore\Models\Template', 'templateable')->where('status', 'active');
    }

    public function getLangAttribute()
    {
        return explode("_", $this->language ?? 'id_ID')[0];
    }
}
