<?php

use Illuminate\Database\Seeder;

class AppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Site::create([
            'domain' => env('APP_DOMAIN', 'localhost'),
            'name' => 'ZetthCMS Core',
            // 'tagline' => '',
            // 'logo' => '',
            // 'icon' => '',
            // 'description' => 'Core System ZetthCMS',
            // 'keyword' => 'zetthcms, cms',
            'status' => 1,
            'active_at' => date("Y-m-d H:i:s"),
            // 'email' => '',
            // 'address' => '',
            // 'phone' => '',
            // 'google_analytics' => '',
            // 'coordinate' => '',
        ]);
    }
}
