<?php

namespace ZetthCore\Seeder;

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
                \ZetthCore\Models\RoleMenu::create([
                    'role_id' => $role->id,
                    'menu_group_id' => 1,
                ]);
            } else if ($role->name == 'admin') {
                \ZetthCore\Models\RoleMenu::create([
                    'role_id' => $role->id,
                    'menu_group_id' => 1,
                ]);
            } else if ($role->name == 'author') {
                \ZetthCore\Models\RoleMenu::create([
                    'role_id' => $role->id,
                    'menu_group_id' => 1,
                ]);
            } else if ($role->name == 'editor') {
                \ZetthCore\Models\RoleMenu::create([
                    'role_id' => $role->id,
                    'menu_group_id' => 1,
                ]);
            }
        }
    }
}
