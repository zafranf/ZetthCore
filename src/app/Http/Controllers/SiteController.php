<?php

namespace ZetthCore\Http\Controllers;

use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    use \ZetthCore\Traits\MainTrait;

    public $breacrumbs;

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'page' => 'Beranda',
            'icon' => '',
            'url' => url('/'),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = \Str::start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = resource_path('themes' . $path);

        return $this->getThemeFiles($path);
    }
}
