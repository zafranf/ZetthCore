<?php

namespace ZetthCore\Models;

use Spatie\TranslationLoader\LanguageLine as BaseLanguageLine;
use ZetthCore\Models\Scopes\SiteScope;

class LanguageLine extends BaseLanguageLine
{

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);
    }
}
