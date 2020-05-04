<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Template::create([
            'name' => 'WebSC',
            'slug' => 'WebSC',
            'description' => null,
            'author' => 'Porisweb',
            'status' => 'active',
            'templateable_type' => 'App\Models\Site',
            'templateable_id' => 1,
        ]);
    }
}
