<?php

namespace ZetthCore\Http\Controllers\Report;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\IntermData;

class IntermController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/report/inbox');
        $this->page_title = 'Kelola Pencarian';
        $this->breadcrumbs[] = [
            'page' => 'Laporan',
            'icon' => '',
            'url' => url($this->adminPath . '/report/inbox'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Pencarian',
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
            'page_subtitle' => 'Daftar Pencarian',
        ];

        return view('zetthcore::AdminSC.report.interm', $data);
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
     * @param  \ZetthCore\Models\IntermData  $interm
     * @return \Illuminate\Http\Response
     */
    public function show(IntermData $interm)
    {
        $this->breadcrumbs[] = [
            'page' => 'Detail',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Detail Pencarian',
            'data' => $interm,
        ];

        /* mark as read */
        $interm->read = 1;
        $interm->save();

        return view('zetthcore::AdminSC.report.interm_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\IntermData  $interm
     * @return \Illuminate\Http\Response
     */
    public function edit(IntermData $interm)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\IntermData  $interm
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, IntermData $interm)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\IntermData  $interm
     * @return \Illuminate\Http\Response
     */
    public function destroy(IntermData $interm)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Pencarian "' . $interm->email . '"');

        /* soft delete */
        $interm->delete();

        return redirect($this->current_url)->with('success', 'Pencarian berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = IntermDataData::select('id', 'host', 'text', 'count')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
