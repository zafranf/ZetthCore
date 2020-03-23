<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $primaryKey = 'email';
    protected $fillable = [
        'email', 'token', 'created_at',
    ];
    protected $dates = ['created_at'];
    public $timestamps = false;
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('ZetthCore\Models\User', 'email', 'email');
    }
}
