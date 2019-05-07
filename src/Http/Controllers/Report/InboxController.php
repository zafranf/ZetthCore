<?php

namespace ZetthCore\Http\Controllers\Report;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Inbox;

class InboxController extends AdminController
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
        $this->page_title = 'Kelola Kotak Masuk';
        $this->breadcrumbs[] = [
            'page' => 'Laporan',
            'icon' => '',
            'url' => url($this->adminPath . '/report/inbox'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Kotak Masuk',
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
            'page_subtitle' => 'Daftar Kotak Masuk',
        ];

        return view('zetthcore::AdminSC.report.inbox', $data);
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
     * @param  \ZetthCore\Models\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function show(Inbox $inbox)
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
            'page_subtitle' => 'Detail Kotak Masuk',
            'data' => $inbox,
        ];

        /* mark as read */
        $inbox->read = 1;
        $inbox->save();

        return view('zetthcore::AdminSC.report.inbox_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Inbox $inbox)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Inbox $inbox)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Inbox  $inbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inbox $inbox)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Kotak Masuk "' . $inbox->email . '"');

        /* soft delete */
        $inbox->delete();

        return redirect($this->current_url)->with('success', 'Kotak Masuk berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Inbox::select(\DB::raw('substring(message, 1, 50) as message'), 'id', 'name', 'email', 'status')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
