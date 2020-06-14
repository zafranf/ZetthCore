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
            'domain' => config('app.domain'),
            'name' => config('app.name'),
            'language' => 'id_ID',
            'status' => 'active',
            'active_at' => now(),
        ]);
    }
}
