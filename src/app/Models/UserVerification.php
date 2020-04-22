<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $dates = ['verified_at'];
    protected $primaryKey = 'user_id';
    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('ZetthCore\Models\User');
    }
}
