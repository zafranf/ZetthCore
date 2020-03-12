<?php

namespace ZetthCore\Http\Controllers\Content\Gallery;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Album;
use ZetthCore\Models\AlbumDetail;

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
        $this->current_url = url(app('admin_path') . '/content/gallery/photos');
        $this->page_title = 'Kelola Foto';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url(app('admin_path') . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Galeri',
            'icon' => '',
            'url' => url(app('admin_path') . '/content/gallery/photos'),
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
        /* set breadcrumbs */
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
            'name' => 'required|max:100|unique:albums,name,NULL,created_at,type,photo',
            'slug' => 'unique:albums,slug,NULL,created_at,type,photo',
            'photos.files.*' => 'required',
        ], [
            'photos.files.*' => 'The photos field is required',
        ]);

        /* save data */
        $album = new Album;
        $album->name = $r->input('name');
        $album->slug = str_slug($album->name);
        $album->description = $r->input('description');
        $album->type = 'photo';
        $album->status = bool($r->input('status')) ? 1 : 0;
        $album->save();

        /* process photos */
        $this->process_photos($r->input('photos'), $album->id);

        /* log aktifitas */
        $this->activityLog('<b>' . app('user')->fullname . '</b> menambahkan album "' . $album->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Album "' . $album->name . '" berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
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
            'page_title' => $this->page_title,
            'breadcrumbs' => $this->breadcrumbs,
            'page_subtitle' => 'Edit Album',
            'data' => $album->load('photos'),
        ];

        return view('zetthcore::AdminSC.content.photos_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Album $album)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required|max:100|unique:albums,name,' . $album->id . ',id,type,photo',
            'slug' => 'unique:albums,slug,' . $album->id . ',id,type,photo',
            'photos.files.*' => 'required',
        ], [
            'photos.files.*' => 'The photos field is required',
        ]);

        /* save data */
        // $album = new Album;
        $album->name = $r->input('name');
        $album->slug = str_slug($album->name);
        $album->description = $r->input('description');
        $album->type = 'photo';
        $album->status = bool($r->input('status')) ? 1 : 0;
        $album->save();

        /* process photos */
        $this->process_photos($r->input('photos'), $album->id);

        /* log aktifitas */
        $this->activityLog('<b>' . app('user')->fullname . '</b> memperbarui album "' . $album->name . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Album "' . $album->name . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        /* log aktifitas */
        $this->activityLog('<b>' . app('user')->fullname . '</b> menghapus album "' . $album->name . '"');

        /* soft delete */
        $album->delete();

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Album "' . $album->name . '" berhasil dihapus!');
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
        $data = Album::select('id', 'name', 'slug', 'status')->with('photo')->withCount('photos')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }

    public function process_photos($photos, $album_id)
    {
        /* mapping photos */
        $data = [];
        foreach ($photos['files'] as $n => $file) {
            $data[] = [
                'file' => $file,
                'description' => $photos['descriptions'][$n],
                // 'status' => 1,
                'album_id' => $album_id,
                'created_at' => \Carbon\Carbon::now(),
            ];
        }

        /* delete existings */
        $del = AlbumDetail::where('album_id', $album_id)->forceDelete();

        /* save all photos */
        $save = AlbumDetail::insert($data);

        return $save;
    }

}
