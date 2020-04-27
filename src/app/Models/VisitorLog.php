<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Builder;

class VisitorLog extends Base
{
    protected $guarded = [];
    public $incrementing = false;

    /**
     * https://stackoverflow.com/a/51691212/6885956
     *
     * @param Builder $query
     * @return void
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * https://stackoverflow.com/a/51691212/6885956
     *
     * @param [type] $keyName
     * @return void
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }

    public function post()
    {
        return $this->hasOne('ZetthCore\Models\Post', 'slug', 'slug')->where('type', 'article');
    }
}
