<?php

namespace ZetthCore\Http\Controllers;

use ZetthCore\Http\Controllers\Controller;

class AdminController extends Controller
{
    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'page' => '',
            'icon' => 'fa fa-home',
            'url' => url($this->adminPath),
        ];
    }
}
