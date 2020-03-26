<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'description', 'method', 'path', 'ip', 'get', 'post', 'files', 'user_id',
    ];
    public $appends = ['created_at_tz'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getCreatedAtTzAttribute()
    {
        return carbon($this->created_at)->format('Y-m-d H:i:s');
    }
}
