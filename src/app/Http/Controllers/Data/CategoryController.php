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
        $this->current_url = url(app('admin_path') . '/data/categories');
        $this->page_title = 'Kelola Kategori';
        $this->breadcrumbs[] = [
            'page' => 'Data',
            'icon' => '',
            'url' => url(app('admin_path') . '/data/users'),
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
            'page_subtitle' => 'Tambah Kategori',
            'categories' => Term::where('type', 'category')->where('parent_id', 0)->with('subcategory_all')->orderBy('name')->get(),
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
        $category = new Term;
        $category->name = $r->input('name');
        $category->slug = str_slug($category->name);
        $category->description = $r->input('description');
        $category->type = 'category';
        $category->parent_id = (int) $r->input('parent');
        $category->status = bool($r->input('status')) ? 1 : 0;
        $category->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Kategori "' . $category->name . '"');

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->name . ' berhasil ditambah!');
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
            'page_subtitle' => 'Edit Kategori',
            'categories' => Term::where('type', 'category')->where('parent_id', 0)->with('subcategory_all')->orderBy('name')->get(),
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
        $category->name = $r->input('name');
        $category->slug = str_slug($category->name);
        $category->description = $r->input('description');
        $category->type = 'category';
        $category->parent_id = (int) $r->input('parent');
        $category->status = bool($r->input('status')) ? 1 : 0;
        $category->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Kategori "' . $category->name . '"');

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->name . ' berhasil disimpan!');
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
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Kategori "' . $category->name . '"');

        /* soft delete */
        $category->delete();

        return redirect($this->current_url)->with('success', 'Kategori ' . $category->name . ' berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Term::select('id', 'name', 'description', 'status')->where('type', 'category')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }
}
