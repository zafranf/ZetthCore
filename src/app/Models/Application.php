<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $dates = ['active_at'];

    public function socmed_data()
    {
        return $this->hasMany('ZetthCore\Models\SocmedData', 'data_id')->where('type', 'config');
    }
}
