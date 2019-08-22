<?php

namespace ZetthCore\Http\Controllers;

use ZetthCore\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url('/setting/roles');
        $this->page_title = 'Dasbor';
        $this->breadcrumbs[] = [
            'page' => 'Dasbor',
            'icon' => '',
            'url' => '',
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Beranda',
            'breadcrumbs' => $this->breadcrumbs,
        ];

        return view('zetthcore::AdminSC.dashboard', $data);
    }
}
