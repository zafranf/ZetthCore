<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.role_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {
            // Create a new role
            $role = \ZetthCore\Models\Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => 'Peran sebagai ' . ucwords(str_replace('_', ' ', $key)),
                'status' => 'active',
            ]);
            $permissions = [];

            $this->command->info('Creating Role ' . strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {
                foreach (explode(',', $value) as $p => $perm) {
                    $permissionValue = $mapPermission->get($perm);

                    $modulename = explode('.', $module);
                    $permissions[] = \ZetthCore\Models\Permission::firstOrCreate([
                        'name' => $module . '.' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucwords(implode(" ", $modulename)),
                        'description' => ucfirst($permissionValue) . ' ' . ucwords(implode(" ", $modulename)),
                    ])->id;

                    $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            /* custom super username */
            $key_info = ($key == 'super') ? 'sa' : $key;
            $key_fullname = ($key == 'super') ? 'Superadmin' : $key;
            $key = ($key == 'super') ? 'sa' : $key;

            $this->command->info("Creating '{$key_info}' user");

            // Create default user for each role
            $user = \App\Models\User::create([
                'name' => $key,
                'fullname' => ucwords(str_replace('_', ' ', $key_fullname)),
                'email' => $key . '@' . config('app.domain'),
                'password' => '123123',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            /* save user detail */
            $user->detail()->create([
                'user_id' => $user->id,
            ]);

            $user->attachRole($role);
        }

        // Creating user with permissions
        if (!empty($userPermission)) {
            foreach ($userPermission as $key => $modules) {
                foreach ($modules as $module => $value) {
                    // Create default user for each permission set
                    $this->command->info("Creating '{$key}' user (from user permission)");
                    $user = \App\Models\User::create([
                        'name' => $key,
                        'fullname' => ucwords(str_replace('_', ' ', $key_fullname)),
                        'email' => $key . '@' . config('app.domain'),
                        'password' => '123123',
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $permissions = [];

                    foreach (explode(',', $value) as $p => $perm) {
                        $permissionValue = $mapPermission->get($perm);
                        $permissions[] = \ZetthCore\Models\Permission::firstOrCreate([
                            'name' => $permissionValue . '-' . $module,
                            'display_name' => 'test' . ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        ])->id;

                        $this->command->info('Creating Permission to ' . $permissionValue . ' for ' . $module);
                    }
                }

                // Attach all permissions to the user
                $user->permissions()->sync($permissions);
            }
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('role_permission')->truncate();
        DB::table('user_permission')->truncate();
        DB::table('role_user')->truncate();
        \App\Models\User::truncate();
        \ZetthCore\Models\Role::truncate();
        \ZetthCore\Models\Permission::truncate();
        Schema::enableForeignKeyConstraints();
    }
}
