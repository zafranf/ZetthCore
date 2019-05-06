<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'description', 'method', 'path', 'ip', 'get', 'post', 'files', 'user_id',
    ];
}
