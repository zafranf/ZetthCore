<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    public $incrementing = false;

    /* protected $primaryKey = [
    'ip', 'page', 'referral', 'agent', 'browser', 'browser_version', 'device', 'device_name', 'os', 'os_version', 'is_robot', 'robot_name', 'count',
    ]; */

    // protected $fillable = ['id', 'ip', 'page', 'referral', 'agent', 'browser', 'browser_version', 'device', 'device_name', 'os', 'os_version', 'is_robot', 'robot_name', 'count', 'created_at', 'updated_at'];

    protected $guard = [];

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
}
