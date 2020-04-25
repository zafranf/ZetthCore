<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class SocmedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \ZetthCore\Models\Socmed::create([
            'name' => "Facebook",
            'url' => "https://facebook.com",
            'icon' => "fa fa-facebook",
            'color' => '',
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\Socmed::create([
            'name' => "Twitter",
            'url' => "https://twitter.com",
            'icon' => "fa fa-twitter",
            'color' => '',
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\Socmed::create([
            'name' => "Instagram",
            'url' => "https://instagram.com",
            'icon' => "fa fa-instagram",
            'color' => '',
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        \ZetthCore\Models\Socmed::create([
            'name' => "Youtube",
            'url' => "https://youtube.com",
            'icon' => "fa fa-youtube",
            'color' => '',
            'status' => 'active',
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
