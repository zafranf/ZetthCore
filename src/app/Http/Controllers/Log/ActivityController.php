<?php

namespace ZetthCore\Http\Controllers\Log;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\ActivityLog;

class ActivityController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/log/activities');
        $this->page_title = 'Catatan Aktifitas';
        $this->breadcrumbs[] = [
            'page' => 'Catatan',
            'icon' => '',
            'url' => _url(adminPath() . '/log/activities'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Aktifitas',
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
            'page_subtitle' => 'Daftar Aktifitas',
        ];

        return view('zetthcore::AdminSC.log.activity', $data);
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
     * @param  \ZetthCore\Models\ActivityLog  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activity)
    {
        /* set breadcrumbs */
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
            'page_subtitle' => 'Detail Aktifitas',
            'act' => $activity,
        ];

        return view('zetthcore::AdminSC.log.activity_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\ActivityLog  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityLog $activity)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\ActivityLog  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, ActivityLog $activity)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\ActivityLog  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(ActivityLog $activity)
    {
        abort(403);
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        $from = $r->get('from') ?? now()->subDays(7);
        $to = $r->get('to');
        if ($from) {
            $r->merge([
                'created_at' => $to ? [$from, $to] : $from,
            ]);
        }

        /* get data */
        $data = ActivityLog::select('id', 'method', 'ip', 'description', 'path', 'created_at', 'user_id')
            ->orderBy('created_at', 'desc')
            ->with('user:id,fullname');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data, ['description', 'path'], [
                'user_id' => 'equal',
                'club_id' => 'equal',
                'created_at' => $from && $to ? 'between' : 'gte',
            ]);
        }

        abort(403);
    }
}
