<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class IntermdataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \ZetthCore\Models\Interm::create([
            'host' => env('APP_DOMAIN', 'localhost'),
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'blogsearch.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'images.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'local.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'maps.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'news.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'video.google.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'blogsearch.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'images.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'local.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'maps.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'news.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'video.google.co.id',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'bing.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'ask.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'aol.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.aol.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'alltheweb.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'yahoo.com',
            'param' => 'p',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.yahoo.com',
            'param' => 'p',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'yahoo.co.id',
            'param' => 'p',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.yahoo.co.id',
            'param' => 'p',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'baidu.com',
            'param' => 'wd',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'yandex.com',
            'param' => 'text',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'alhea.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'dogpile.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'duckduckgo.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'hotbot.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'info.com',
            'param' => 'qkw',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'looksmart.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'msxml.excite.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.earthlink.net',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.lycos.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.msn.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'search.infospace.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'sogou.com',
            'param' => 'query',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'uk.ask.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'id.ask.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'qwant.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'wow.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
        \ZetthCore\Models\Interm::create([
            'host' => 'zapmeta.com',
            'param' => 'q',
            'status' => 'active',
            'created_at' => now(),
        ]);
    }
}
