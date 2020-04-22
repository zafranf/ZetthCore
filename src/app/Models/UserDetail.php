<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = ['*'];
}
