<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
        $this->call(AppTableSeeder::class);
        $this->call(IntermdataSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(SocmedSeeder::class);
        $this->call(MenuGroupSeeder::class);
        $this->call(RoleMenuSeeder::class);
    }
}
