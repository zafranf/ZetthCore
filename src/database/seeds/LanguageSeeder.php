<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Language::create([
            'name' => 'id',
            'description' => 'Bahasa Indonesia',
        ]);
        \ZetthCore\Models\Language::create([
            'name' => 'en',
            'description' => 'English',
        ]);
    }
}
