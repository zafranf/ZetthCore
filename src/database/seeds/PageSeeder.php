<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Post::create([
            'title' => 'Tentang Kami',
            'slug' => 'about',
            'content' => '<p>Ini adalah contoh halaman <b>Tentang Kami</b>. Silakan sesuaikan isinya di sini.</p>',
            'type' => 'page',
            'status' => 'active',
            'created_by' => 2,
        ]);
    }
}
