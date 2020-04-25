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
            'name' => "Admin",
            'slug' => "admin",
            'description' => "Grup menu untuk admin",
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\MenuGroup::create([
            'name' => "Website",
            'slug' => "website",
            'description' => "Grup menu untuk web",
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
