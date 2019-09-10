<?php

namespace ZetthCore\Http\Controllers\Report;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\PostComment;

class CommentController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/report/comments');
        $this->page_title = 'Kelola Komentar';
        $this->breadcrumbs[] = [
            'page' => 'Laporan',
            'icon' => '',
            'url' => url($this->adminPath . '/report/comments'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Komentar',
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
            'page_subtitle' => 'Daftar Komentar',
        ];

        return view('zetthcore::AdminSC.report.comment', $data);
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
     * @param  \ZetthCore\Models\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(PostComment $comment)
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
            'page_subtitle' => 'Detail Komentar',
            'data' => $comment,
        ];

        /* mark as read */
        $comment->read = 1;
        $comment->save();

        return view('zetthcore::AdminSC.report.comment_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(PostComment $comment)
    {
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\PostComment  $inbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, PostComment $comment)
    {
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\PostComment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostComment $comment)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Komentar "' . $comment->email . '"');

        /* soft delete */
        $comment->delete();

        return redirect($this->current_url)->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = PostComment::select('id', 'name', 'email', \DB::raw('substring(comment, 1, 100) as comment'), 'status')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}