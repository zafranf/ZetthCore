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
        $this->current_url = url('/report/subscribers');
        $this->page_title = 'Pengaturan Pelanggan';
        $this->breadcrumbs[] = [
            'page' => 'Pelanggan Info',
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
        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Daftar Label',
        ];

        return view('admin.report.subscriber', $data);
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
        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Pelanggan',
            'data' => $subscriber,
        ];

        return view('admin.report.subscriber_form', $data);
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
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Pelanggan "' . $subscriber->email . '"');

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
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Pelanggan "' . $subscriber->email . '"');

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
        $data = Subscriber::select(sequence(), 'id', 'email', 'status')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
