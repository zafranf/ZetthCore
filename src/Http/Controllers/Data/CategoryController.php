<?php

namespace ZetthCore\Http\Controllers\Data;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Term;

class CategoryController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/data/categories');
        $this->page_title = 'Kelola Kategori';
        $this->breadcrumbs[] = [
            'page' => 'Data',
            'icon' => '',
            'url' => url($this->adminPath . '/data/users'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Kategori',
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
            'page_subtitle' => 'Daftar Kategori',
        ];

        return view('zetthcore::AdminSC.data.categories', $data);
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
            'page_subtitle' => 'Tambah Kategori',
            'categories' => Term::where('type', 'category')->where('parent_id', 0)->with('allSubcategory')->orderBy('name')->get(),
        ];

        return view('zetthcore::AdminSC.data.categories_form', $data);
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
            'name' => 'required|unique:terms,name,NULL,created_at,type,category',
        ]);

        /* save data */
        $name = str_sanitize($r->input('name'));
        $category = new Term;
        $category->name = str_slug($name);
        $category->display_name = $name;
        $category->description = str_sanitize($r->input('description'));
        $category->type = 'category';
        $category->parent_id = (int) $r->input('parent');
        $category->status = bool($r->input('status')) ? 1 : 0;
        $category->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Kategori "' . $category->display_name . '"');

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->display_name . ' berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Term  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Term  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Term $category)
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
            'page_subtitle' => 'Edit Kategori',
            'categories' => Term::where('type', 'category')->where('parent_id', 0)->with('allSubcategory')->orderBy('name')->get(),
            'data' => $category,
        ];

        return view('zetthcore::AdminSC.data.categories_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Term  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Term $category)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required|unique:terms,name,' . $category->id . ',id,type,category',
        ]);

        /* save data */
        $name = str_sanitize($r->input('name'));
        $category->name = str_slug($name);
        $category->display_name = $name;
        $category->description = str_sanitize($r->input('description'));
        $category->type = 'category';
        $category->parent_id = (int) $r->input('parent');
        $category->status = bool($r->input('status')) ? 1 : 0;
        $category->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Kategori "' . $category->display_name . '"');

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->display_name . ' berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Term  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $category)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Kategori "' . $category->display_name . '"');

        /* soft delete */
        $category->delete();

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->display_name . ' berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Term::select('id', 'display_name as name', 'description', 'status')->where('type', 'category')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
