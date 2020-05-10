<?php

namespace ZetthCore\Http\Controllers\Log;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\VisitorLog;

class VisitorController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(adminPath() . '/log/visitors');
        $this->page_title = 'Catatan Pengunjung';
        $this->breadcrumbs[] = [
            'page' => 'Catatan',
            'icon' => '',
            'url' => url(adminPath() . '/log/activities'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Pengunjung',
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
        /* set breadcrumbs */
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
            'page_subtitle' => 'Daftar Pengunjung',
        ];

        return view('zetthcore::AdminSC.log.visitor', $data);
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
     * @param  \ZetthCore\Models\VisitorLog  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(VisitorLog $visitor)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\VisitorLog  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(VisitorLog $visitor)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\VisitorLog  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, VisitorLog $visitor)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\VisitorLog  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(VisitorLog $visitor)
    {
        abort(403);
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = VisitorLog::select('ip', 'page', \DB::raw("if(referral='', '-', referral) as referral"), 'count', 'updated_at')->orderBy('updated_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }
}
