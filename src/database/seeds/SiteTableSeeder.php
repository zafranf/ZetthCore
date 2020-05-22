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
            'language' => 'id_ID',
            'status' => 'active',
            'active_at' => now(),
        ]);
    }
}
