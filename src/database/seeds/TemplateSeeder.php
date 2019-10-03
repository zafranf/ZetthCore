<?php

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
        \DB::table('templates')->insert([
            'name' => 'WebSC',
            'slug' => 'WebSC',
            'description' => null,
            'author' => 'Porisweb',
            'status' => 1,
        ]);
    }
}
