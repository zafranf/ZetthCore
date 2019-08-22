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
        $this->current_url = url($this->adminPath . '/data/tags');
        $this->page_title = 'Kelola Label';
        $this->breadcrumbs[] = [
            'page' => 'Data',
            'icon' => '',
            'url' => url($this->adminPath . '/data/users'),
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
            // 'tags' => Term::where('type', 'tag')->where('parent_id', 0)->with('allSubtag')->orderBy('name')->get(),
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
        $name = str_sanitize($r->input('name'));
        $tag = new Term;
        $tag->name = str_slug($name);
        $tag->display_name = $name;
        $tag->description = str_sanitize($r->input('description'));
        $tag->type = 'tag';
        $tag->status = bool($r->input('status')) ? 1 : 0;
        $tag->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Label "' . $tag->display_name . '"');

        return redirect($this->current_url)->with('success', 'Label ' . $tag->display_name . ' berhasil ditambah!');
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
            // 'tags' => Term::where('type', 'tag')->where('parent_id', 0)->with('allSubtag')->orderBy('name')->get(),
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
        $name = str_sanitize($r->input('name'));
        $tag->name = str_slug($name);
        $tag->display_name = $name;
        $tag->description = str_sanitize($r->input('description'));
        $tag->type = 'tag';
        $tag->status = bool($r->input('status')) ? 1 : 0;
        $tag->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Label "' . $tag->display_name . '"');

        return redirect($this->current_url)->with('success', 'Label ' . $tag->display_name . ' berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Term  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $tag)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Label "' . $tag->display_name . '"');

        /* soft delete */
        $tag->delete();

        return redirect($this->current_url)->with('success', 'Label ' . $tag->display_name . ' berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Term::select('id', 'display_name as name', 'description', 'status')->where('type', 'tag')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
