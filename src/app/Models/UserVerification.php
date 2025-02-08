<?php

namespace ZetthCore\Models;

class UserVerification extends Base
{
    protected $casts = [
        'verified_at' => 'datetime',
    ];
    protected $primaryKey = 'user_id';
    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
