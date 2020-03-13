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
            'url' => '/',
        ];
    }
}
