<?php

namespace ZetthCore\Models;

use Spatie\TranslationLoader\LanguageLine as BaseLanguageLine;
use ZetthCore\Models\Scopes\SiteScope;

class LanguageLine extends BaseLanguageLine
{
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new SiteScope);

        $flushGroupCache = function (self $languageLine) {
            $languageLine->flushGroupCache();
        };

        static::saved($flushGroupCache);
        static::deleted($flushGroupCache);
    }
}
