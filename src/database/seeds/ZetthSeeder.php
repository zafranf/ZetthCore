<?php

namespace ZetthCore\Seeder;

use Illuminate\Database\Seeder;

class ZetthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SiteTableSeeder::class);
        $this->call(TemplateSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(MenuGroupSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(GuideSeeder::class);
        $this->call(RoleMenuSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(IntermdataSeeder::class);
        $this->call(SocmedSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(LanguageSeeder::class);
    }
}
