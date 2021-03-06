<?php

namespace ZetthCore\Http\Controllers\Data;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Term;

class TagController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/data/tags');
        $this->page_title = 'Kelola Label';
        $this->breadcrumbs[] = [
            'page' => 'Data',
            'icon' => '',
            'url' => _url(adminPath() . '/data/users'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Label',
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
            'page_subtitle' => 'Daftar Label',
        ];

        return view('zetthcore::AdminSC.data.tags', $data);
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
            'page_subtitle' => 'Tambah Label',
        ];

        return view('zetthcore::AdminSC.data.tags_form', $data);
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
            'name' => 'required|unique:terms,name,NULL,created_at,type,tag',
        ]);

        /* save data */
        $tag = new Term;
        $tag->name = $r->input('name');
        $tag->slug = \Str::slug($tag->name);
        $tag->description = $r->input('description');
        $tag->type = 'tag';
        $tag->group = 'post';
        $tag->status = $r->input('status') ?? 'inactive';
        $tag->site_id = app('site')->id;
        $tag->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menambahkan label "' . $tag->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Label ' . $tag->name . ' berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Term  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Term  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $tag)
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
            'page_subtitle' => 'Edit Label',
            'data' => $tag,
        ];

        return view('zetthcore::AdminSC.data.tags_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Term  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Term $tag)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required|unique:terms,name,' . $tag->id . ',id,type,tag',
        ]);

        /* save data */
        $tag->name = $r->input('name');
        $tag->slug = \Str::slug($tag->name);
        $tag->description = $r->input('description');
        $tag->type = 'tag';
        $tag->group = 'post';
        $tag->status = $r->input('status') ?? 'inactive';
        $tag->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui label "' . $tag->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Label ' . $tag->name . ' berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Term  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $tag)
    {
        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus label "' . $tag->name . '"');

        /* soft delete */
        $tag->delete();

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Label ' . $tag->name . ' berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Term::select('id', 'name', 'description', 'status')
            ->where('type', 'tag')
            ->where('group', 'post')
            ->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }
}
