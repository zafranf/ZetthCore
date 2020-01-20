<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class SiteTableSeeder extends Seeder
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
            'name' => env('APP_NAME', 'localhost'),
            // 'tagline' => '',
            // 'logo' => '',
            // 'icon' => '',
            // 'description' => 'Core System ZetthCMS',
            // 'keywords' => 'zetthcms, cms',
            'template_id' => 1,
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
