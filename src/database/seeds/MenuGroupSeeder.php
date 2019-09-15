<?php

use Illuminate\Database\Seeder;

class MenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_groups')->insert([
            'name' => "Super",
            'slug' => "super",
            'description' => "Grup menu untuk super",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('menu_groups')->insert([
            'name' => "Admin",
            'slug' => "admin",
            'description' => "Grup menu untuk admin",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('menu_groups')->insert([
            'name' => "Website",
            'slug' => "website",
            'description' => "Grup menu untuk web",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
