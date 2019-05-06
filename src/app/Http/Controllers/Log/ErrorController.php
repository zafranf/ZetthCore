<?php

namespace ZetthCore\Http\Controllers\Log;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\ErrorLog;

class ErrorController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/log/errors');
        $this->page_title = 'Catatan Galat';
        $this->breadcrumbs[] = [
            'page' => 'Catatan',
            'icon' => '',
            'url' => url($this->adminPath . '/log/activities'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Galat',
            'icon' => '',
            'url' => $this->current_url,
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->breadcrumbs[] = [
            'page' => 'Daftar',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Daftar Galat',
        ];

        return view('admin.AdminSC.log.error', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function show(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function edit(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\ErrorLog  $error
     * @return \Illuminate\Http\Response
     */
    public function destroy(ErrorLog $error)
    {
        abort(403);
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = ErrorLog::select('message', \DB::raw("replace(file, '" . base_path() . "', '') as file"), 'line', 'count', 'updated_at')->orderBy('updated_at', 'desc')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
