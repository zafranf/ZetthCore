<?php

use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \ZetthCore\Models\Role::all();
        foreach ($roles as $role) {
            if ($role->name == 'super') {
                $menus = \ZetthCore\Models\MenuGroup::all();
                foreach ($menus as $menu) {
                    \ZetthCore\Models\RoleMenu::create([
                        'role_id' => $role->id,
                        'menu_group_id' => $menu->id,
                    ]);
                }
            } else if ($role->name == 'admin') {
                $menu = \ZetthCore\Models\MenuGroup::first();
                // foreach ($menus as $menu) {
                    \ZetthCore\Models\RoleMenu::create([
                        'role_id' => $role->id,
                        'menu_group_id' => $menu->id,
                    ]);
                // }
            }
        }
    }
}
