<?php

namespace ZetthCore\Http\Controllers\Content;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Post;

class PageController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/content/pages');
        $this->page_title = 'Kelola Halaman';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url($this->adminPath . '/content/pages'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Halaman',
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
            'page_subtitle' => 'Daftar Halaman',
        ];

        return view('admin.AdminSC.content.pages', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->breadcrumbs[] = [
            'page' => 'Tambah',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Tambah Halaman',
        ];

        return view('admin.AdminSC.content.pages_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,NULL,created_at,type,page',
            'slug' => 'required|max:100|unique:posts,slug,NULL,created_at,type,page',
            'content' => 'required',
        ]);

        /* save data */
        $page = new Post;
        $page->title = $r->input('title');
        $page->slug = str_slug($r->input('slug'));
        $page->content = $r->input('content');
        $page->type = 'page';
        $page->status = bool($r->input('status')) ? 1 : 0;
        $page->created_by = \Auth::user()->id;
        $page->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Halaman "' . $page->title . '"');

        return redirect($this->current_url)->with('success', 'Halaman "' . $page->title . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $page
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $page)
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
            'page_subtitle' => 'Edit Halaman',
            'data' => $page,
        ];

        return view('admin.AdminSC.content.pages_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Post  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Post $page)
    {
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,' . $page->id . ',id,type,page',
            'slug' => 'required|max:100|unique:posts,slug,' . $page->id . ',id,type,page',
            'content' => 'required',
        ]);

        /* save data */
        $page->title = $r->input('title');
        $page->slug = str_slug($r->input('slug'));
        $page->content = $r->input('content');
        $page->type = 'page';
        $page->status = bool($r->input('status')) ? 1 : 0;
        $page->updated_by = \Auth::user()->id;
        $page->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Halaman "' . $page->title . '"');

        return redirect($this->current_url)->with('success', 'Halaman "' . $page->title . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Post  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $page)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Halaman "' . $page->title . '"');

        /* soft delete */
        $page->delete();

        return redirect($this->current_url)->with('success', 'Halaman "' . $page->title . '" berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Post::select('id', 'title', /* 'slug', */'status')->where('type', 'page')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
