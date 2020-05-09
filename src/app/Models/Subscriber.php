<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Subscriber extends Base
{
    use SoftDeletes;

    protected $guarded = [];
    public $appends = ['created_at_tz'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getCreatedAtTzAttribute()
    {
        return carbon($this->created_at)->format('Y-m-d H:i:s');
    }
}
