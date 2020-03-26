<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class UserOauth extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'driver', 'driver_uid', 'raw_data'];
}
