<?php

namespace ZetthCore\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SiteScope implements Scope
{
    /** @var \ZetthCore\Models\Site|null */
    private $site;

    /** Guard anti-rekursi: true selama getSiteConfig() sedang berjalan. */
    private static $resolving = false;

    public function __construct()
    {
        /*
         * JANGAN query di constructor. Scope ini di-instansiasi saat Eloquent
         * boot model (Base/User/LanguageLine::booted), dan sejak Laravel 13
         * query terhadap model yang sedang boot melempar LogicException.
         * Resolve lazily di apply().
         */
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        /*
         * getSiteConfig() menjalankan query Site + eager load (socmed, template).
         * Model hasil eager load itu juga memakai scope ini, jadi tanpa guard
         * pemanggilannya berulang tanpa henti (Cache::remember belum terisi
         * sampai callback selesai). Selama resolusi berlangsung, query turunan
         * dibiarkan tanpa filter site.
         */
        if (self::$resolving) {
            return;
        }

        if (is_null($this->site)) {
            self::$resolving = true;

            try {
                $this->site = getSiteConfig();
            } finally {
                self::$resolving = false;
            }
        }

        $builder->where($model->getTable() . '.site_id', $this->site->id);
    }
}
