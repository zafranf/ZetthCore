<?php

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

        DB::table('socmeds')->insert([
            'name' => "Facebook",
            'url' => "https://facebook.com",
            'icon' => "fa fa-facebook",
            'color' => '',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('socmeds')->insert([
            'name' => "Twitter",
            'url' => "https://twitter.com",
            'icon' => "fa fa-twitter",
            'color' => '',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('socmeds')->insert([
            'name' => "Instagram",
            'url' => "https://instagram.com",
            'icon' => "fa fa-instagram",
            'color' => '',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('socmeds')->insert([
            'name' => "Youtube",
            'url' => "https://youtube.com",
            'icon' => "fa fa-youtube",
            'color' => '',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
