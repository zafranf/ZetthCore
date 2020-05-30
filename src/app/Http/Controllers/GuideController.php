<?php

namespace ZetthCore\Http\Controllers;

use ZetthCore\Http\Controllers\AdminController;

class GuideController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/guide');
        $this->page_title = 'Panduan Pengguna';
        $this->breadcrumbs[] = [
            'page' => 'Panduan Pengguna',
            'icon' => '',
            'url' => '',
        ];
    }

    public function index()
    {
        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Panduan Pengguna',
            'data' => \ZetthCore\Models\Guide::whereNull('parent_id')
                ->with('subguide')
                ->orderBy('order')
                ->get(),
        ];

        return view('zetthcore::AdminSC.guide', $data);
    }
}
