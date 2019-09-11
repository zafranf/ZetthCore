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
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,NULL,created_at,type,article',
            'slug' => 'unique:posts,slug,NULL,created_at,type,article',
            'content' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        /* set variables */
        $title = $r->input('title');
        $slug = str_slug($r->input('slug'));
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
        $post = new Post;
        $post->title = $title;
        $post->slug = $slug;
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt');
        $post->type = 'article';
        $post->cover = $cover;
        $post->status = $r->input('status');
        $post->share = ($r->input('share')) ? 1 : 0;
        $post->like = ($r->input('like')) ? 1 : 0;
        $post->comment = ($r->input('comment')) ? 1 : 0;
        $post->published_at = $date . ' ' . $time;
        // $post->short_url = $uniq;
        $post->created_by = \Auth::user()->id;
        $post->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Album "' . $post->title . '"');

        return redirect($this->current_url)->with('success', 'Album "' . $post->title . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
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
            'data' => $post->load('terms'),
        ];

        return view('zetthcore::AdminSC.content.photos_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Post $post)
    {
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,' . $post->id . ',id,type,article',
            // 'slug' => 'unique:posts,slug,' . $post->id . ',id,type,article',
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
        $post->title = $title;
        // $post->slug = $slug;
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt');
        $post->type = 'article';
        $post->cover = $cover;
        if ($r->input('cover_remove')) {
            $post->cover = '';
        }
        $post->status = $r->input('status');
        $post->share = ($r->input('share')) ? 1 : 0;
        $post->like = ($r->input('like')) ? 1 : 0;
        $post->comment = ($r->input('comment')) ? 1 : 0;
        $post->published_at = $date . ' ' . $time;
        // $post->short_url = $uniq;
        $post->updated_by = \Auth::user()->id;
        $post->save();

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Album "' . $post->title . '"');

        return redirect($this->current_url)->with('success', 'Album "' . $post->title . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Album "' . $post->title . '"');

        /* soft delete */
        $post->delete();

        return redirect($this->current_url)->with('success', 'Album "' . $post->title . '" berhasil dihapus!');
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
