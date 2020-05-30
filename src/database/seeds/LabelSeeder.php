<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Term::create([
            'name' => 'Umum',
            'slug' => 'umum',
            'description' => 'Label umum',
            'type' => 'tag',
            'group' => 'post',
            'status' => 'active',
        ]);
    }
}
