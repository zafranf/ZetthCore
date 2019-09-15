<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IntermData extends Model
{
    use SoftDeletes;
    protected $table = 'interm_datas';
}
