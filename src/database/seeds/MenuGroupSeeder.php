<?php

namespace ZetthCore\Seeder;

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
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Super",
            'slug' => "super",
            'description' => "Grup menu untuk super",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Admin",
            'slug' => "admin",
            'description' => "Grup menu untuk admin",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Author",
            'slug' => "author",
            'description' => "Grup menu untuk author",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Editor",
            'slug' => "editor",
            'description' => "Grup menu untuk editor",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Website",
            'slug' => "website",
            'description' => "Grup menu untuk web",
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
