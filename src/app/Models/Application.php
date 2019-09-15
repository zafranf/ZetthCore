<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function socmed_data()
    {
        return $this->hasMany('ZettCore\Models\SocmedData')->where('type', 'config');
    }
}
