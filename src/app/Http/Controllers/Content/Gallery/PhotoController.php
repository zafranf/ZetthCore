<?php

namespace ZetthCore\Http\Controllers\Content\Gallery;

use App\Models\Album;
use App\Models\File;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;

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
        $this->current_url = url(adminPath() . '/content/gallery/photos');
        $this->page_title = 'Kelola Foto';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url(adminPath() . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Galeri',
            'icon' => '',
            'url' => url(adminPath() . '/content/gallery/photos'),
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
        $album->slug = \Str::slug($album->name);
        $album->description = $r->input('description');
        $album->type = 'photo';
        $album->status = $r->input('status') ?? 'inactive';
        $album->site_id = app('site')->id;
        $album->save();

        /* process photos */
        $this->process_photos($r->input('photos'), $album->id);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menambahkan album "' . $album->name . '"');

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
        $album->slug = \Str::slug($album->name);
        $album->description = $r->input('description');
        $album->type = 'photo';
        $album->status = $r->input('status') ?? 'inactive';
        $album->save();

        /* process photos */
        $this->process_photos($r->input('photos'), $album->id);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui album "' . $album->name . '"');

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
        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus album "' . $album->name . '"');

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
            $info = pathinfo($file);
            $file = str_replace(url('/'), '', $file);
            $fgc = $this->fgc($file);
            $data[] = [
                'name' => $info['filename'],
                'file' => $file,
                'description' => $photos['descriptions'][$n],
                'type' => 'image',
                'mime' => $fgc['mime'],
                'size' => $fgc['size'],
                'fileable_type' => 'App\Models\Album',
                'fileable_id' => $album_id,
                'created_by' => app('user')->id,
                'created_at' => now(),
                'updated_at' => now(),
                'site_id' => app('site')->id,
            ];
        }

        /* delete existings */
        $del = File::where('fileable_type', 'App\Models\Album')->where('fileable_id', $album_id)->forceDelete();

        /* save all photos */
        $save = File::insert($data);

        return $save;
    }

    private function fgc($path)
    {
        $path = str_replace('storage', 'storage/app/public', $path);
        $path = base_path($path);

        return [
            'size' => \File::size($path),
            'mime' => \File::mimeType($path),
        ];
    }

}
