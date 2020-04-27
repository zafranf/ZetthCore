<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Model;
use ZetthCore\Models\Scopes\SiteScope;

class Base extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }
}
