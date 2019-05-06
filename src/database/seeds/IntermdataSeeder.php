<?php

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
        DB::table('interms')->insert([
            'host' => env('APP_DOMAIN'),
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'blogsearch.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'images.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'local.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'maps.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'news.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'video.google.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'blogsearch.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'images.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'local.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'maps.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'news.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'video.google.co.id',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'bing.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'ask.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'aol.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.aol.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'alltheweb.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'yahoo.com',
            'param' => 'p',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.yahoo.com',
            'param' => 'p',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'yahoo.co.id',
            'param' => 'p',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.yahoo.co.id',
            'param' => 'p',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'baidu.com',
            'param' => 'wd',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'yandex.com',
            'param' => 'text',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'alhea.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'dogpile.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'duckduckgo.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'hotbot.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'info.com',
            'param' => 'qkw',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'looksmart.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'msxml.excite.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.earthlink.net',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.lycos.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.msn.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'search.infospace.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'sogou.com',
            'param' => 'query',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'uk.ask.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'id.ask.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'qwant.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'wow.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
        DB::table('interms')->insert([
            'host' => 'zapmeta.com',
            'param' => 'q',
            'status' => 1,
            'created_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
