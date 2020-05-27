<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
            'description' => 'Kategori umum',
            'type' => 'category',
            'group' => 'post',
            'status' => 'active',
        ]);
    }
}
