<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $dates = ['verified_at'];
    protected $primaryKey = 'user_id';
    protected $fillable = ['*'];
    public $timestamps = false;
}
