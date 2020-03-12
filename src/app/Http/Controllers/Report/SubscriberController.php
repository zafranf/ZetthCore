<?php

namespace ZetthCore\Http\Controllers\Report;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Subscriber;

class SubscriberController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(app('admin_path') . '/report/inbox');
        $this->page_title = 'Kelola Langganan Info';
        $this->breadcrumbs[] = [
            'page' => 'Laporan',
            'icon' => '',
            'url' => url(app('admin_path') . '/report/inbox'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Langganan Info',
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
            'page_subtitle' => 'Daftar Langganan Info',
        ];

        return view('zetthcore::AdminSC.report.subscriber', $data);
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
     * @param  \ZetthCore\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscriber $subscriber)
    {
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Langganan Info',
            'data' => $subscriber,
        ];

        return view('zetthcore::AdminSC.report.subscriber_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Subscriber $subscriber)
    {
        /* validation */
        $this->validate($r, [
            'email' => 'required|email|unique:subscribers,email,' . $subscriber->id . ',id',
        ]);

        /* save data */
        $subscriber->email = str_sanitize($r->input('email'));
        $subscriber->token = str_sanitize($r->input('token'));
        $subscriber->status = bool($r->input('status')) ? 1 : 0;
        $subscriber->save();

        /* log aktifitas */
        $this->activityLog('<b>' . app('user')->fullname . '</b> memperbarui Pelanggan "' . $subscriber->email . '"');

        return redirect($this->current_url)->with('success', 'Pelanggan berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . app('user')->fullname . '</b> menghapus Pelanggan "' . $subscriber->email . '"');

        /* soft delete */
        $subscriber->delete();

        return redirect($this->current_url)->with('success', 'Pelanggan berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Subscriber::select('id', 'email', 'status')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }
}
