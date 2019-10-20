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
        $this->current_url = url(app('admin_path') . '/content/pages');
        $this->page_title = 'Kelola Halaman';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url(app('admin_path') . '/content/pages'),
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
            'page_subtitle' => 'Daftar Halaman',
        ];

        return view('zetthcore::AdminSC.content.pages', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* set breadcrumbs */
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

        return view('zetthcore::AdminSC.content.pages_form', $data);
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
            'title' => 'required|max:100|unique:posts,title,NULL,created_at,type,page,deleted_at,NULL',
            'slug' => 'required|max:100|unique:posts,slug,NULL,created_at,type,page,deleted_at,NULL',
            'content' => 'required',
        ]);

        /* save data */
        $page = new Post;
        $page->title = $r->input('title');
        $page->slug = $r->input('slug') ?? str_slug($page->title);
        $page->content = $r->input('content');
        $page->excerpt = substr(strip_tags($page->content), 0, 255);
        $page->type = 'page';
        $page->status = bool($r->input('status')) ? 1 : 0;
        $page->created_by = \Auth::user()->id;
        $page->published_at = now();
        $page->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan halaman "' . $page->title . '"');

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
        /* set breadcrumbs */
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

        return view('zetthcore::AdminSC.content.pages_form', $data);
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
            'title' => 'required|max:100|unique:posts,title,' . $page->id . ',id,type,page,deleted_at,NULL',
            // 'slug' => 'required|max:100|unique:posts,slug,' . $page->id . ',id,type,page',
            'content' => 'required',
        ]);

        /* save data */
        $page->title = $r->input('title');
        // $page->slug = str_slug($r->input('slug'));
        $page->content = $r->input('content');
        $page->excerpt = substr(strip_tags($page->content), 0, 255);
        $page->type = 'page';
        $page->status = bool($r->input('status')) ? 1 : 0;
        $page->updated_by = \Auth::user()->id;
        $page->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui halaman "' . $page->title . '"');

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
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus halaman "' . $page->title . '"');

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
        $data = Post::select('id', 'title', /* 'slug', */'status')->where('type', 'page')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }
}
