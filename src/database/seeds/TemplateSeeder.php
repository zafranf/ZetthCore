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
            'type' => 'site',
            'status' => 'yes',
        ]);
    }
}
