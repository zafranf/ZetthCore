<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function socmed_data()
    {
        return $this->hasMany('ZetthCore\Models\SocmedData', 'data_id')->where('type', 'config');
    }
}
