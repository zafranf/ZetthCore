<?php

namespace ZetthCore\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    use SoftDeletes;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public function menu_groups()
    {
        return $this->belongsToMany('ZetthCore\Models\MenuGroup', 'role_menu', 'role_id', 'menu_group_id')->with('menu.submenu', 'menu.group');
    }

    /**
     * Undocumented function
     *
     * @param Role $role
     * @return void
     */
    public function roleMenus(\ZetthCore\Models\Role $role)
    {
        $menus = collect([]);
        foreach ($role->menu_groups as $group) {
            $menus = $menus->merge($group->menu);
        }

        return $menus;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
