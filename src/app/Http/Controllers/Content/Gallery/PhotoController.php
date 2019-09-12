<?php

namespace ZetthCore\Http\Controllers\Content\Gallery;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Album;

class PhotoController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url($this->adminPath . '/content/gallery/photos');
        $this->page_title = 'Kelola Foto';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url($this->adminPath . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Galeri',
            'icon' => '',
            'url' => url($this->adminPath . '/content/gallery/photos'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Foto',
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
            'page_subtitle' => 'Daftar Album Foto',
        ];

        return view('zetthcore::AdminSC.content.photos', $data);
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
            'page_title' => $this->page_title,
            'breadcrumbs' => $this->breadcrumbs,
            'page_subtitle' => 'Tambah Album',
        ];

        return view('zetthcore::AdminSC.content.photos_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        dd($r->input());
        /* validation */
        $this->validate($r, [
            'name' => 'required|max:100|unique:albums,title,NULL,created_at,type,photo',
            'slug' => 'unique:albums,slug,NULL,created_at,type,photo',
        ]);

        /* set variables */
        $name = $r->input('name');
        $slug = str_slug($r->input('slug'));

        /* save data */
        $album = new Album;
        $album->name = $name;
        $album->slug = $slug;
        $album->description = $r->input('description');
        $album->type = 'photo';
        $album->status = bool($r->input('status')) ? 1 : 0;
        $album->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Album "' . $album->name . '"');

        return redirect($this->current_url)->with('success', 'Album "' . $album->name . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Post $album)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $album)
    {
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'breadcrumbs' => $this->breadcrumbs,
            'page_subtitle' => 'Edit Album',
            'data' => $album->load('terms'),
        ];

        return view('zetthcore::AdminSC.content.photos_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Post  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Post $album)
    {
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,' . $album->id . ',id,type,photo',
            // 'slug' => 'unique:posts,slug,' . $album->id . ',id,type,photo',
            'content' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        /* set variables */
        $title = $r->input('title');
        // $slug = str_slug($r->input('slug'));
        $categories = $r->input('categories');
        $descriptions = $r->input('descriptions');
        $parents = $r->input('parents');
        $tags = explode(",", $r->input('tags'));
        // $digit = 3;
        // $uniq = str_random($digit);
        $cover = str_replace(url('/'), '', $r->input('cover'));
        $date = ($r->input('date') == '') ? date("Y-m-d") : $r->input('date');
        $time = ($r->input('time') == '') ? date("H:i") : $r->input('time');

        /* save data */
        $album->title = $title;
        // $album->slug = $slug;
        $album->content = $r->input('content');
        $album->excerpt = $r->input('excerpt');
        $album->type = 'article';
        $album->cover = $cover;
        if ($r->input('cover_remove')) {
            $album->cover = '';
        }
        $album->status = $r->input('status');
        $album->share = ($r->input('share')) ? 1 : 0;
        $album->like = ($r->input('like')) ? 1 : 0;
        $album->comment = ($r->input('comment')) ? 1 : 0;
        $album->published_at = $date . ' ' . $time;
        // $album->short_url = $uniq;
        $album->updated_by = \Auth::user()->id;
        $album->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Album "' . $album->title . '"');

        return redirect($this->current_url)->with('success', 'Album "' . $album->title . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Post  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $album)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Album "' . $album->title . '"');

        /* soft delete */
        $album->delete();

        return redirect($this->current_url)->with('success', 'Album "' . $album->title . '" berhasil dihapus!');
    }

    /**
     * Undocumented function
     *
     * @param Request $r
     * @return void
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Album::select('id', 'name', 'slug', 'status')->orderBy('id', 'desc')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
        }

        abort(403);
    }

}
