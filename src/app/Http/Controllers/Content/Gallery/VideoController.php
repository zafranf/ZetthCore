<?php

namespace ZetthCore\Http\Controllers\Content\Gallery;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Post;

class VideoController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/content/gallery/videos');
        $this->page_title = 'Kelola Video';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => _url(adminPath() . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Video',
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
            'page_subtitle' => 'Daftar Video',
        ];

        return view('zetthcore::AdminSC.content.videos', $data);
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
            'page_subtitle' => 'Tambah Video',
        ];

        return view('zetthcore::AdminSC.content.videos_form', $data);
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
            'title' => 'required|max:100|unique:posts,title,NULL,created_at,type,video',
            // 'slug' => 'unique:posts,slug,NULL,created_at,type,video',
            'content' => 'required',
        ]);

        /* save data */
        $video = new Post;
        $video->title = $r->input('title');
        $video->slug = \Str::slug($video->title);
        $video->content = $r->input('content');
        $video->excerpt = substr(strip_tags($video->content), 0, 255);
        $video->type = 'video';
        $video->cover = $r->input('cover');
        $video->status = $r->input('status') ?? 'inactive';
        $video->created_by = app('user')->id;
        $video->published_at = now();
        $video->site_id = app('site')->id;
        $video->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menambahkan video "' . $video->title . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Video "' . $video->title . '" berhasil ditambah!');
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
     * @param  \ZetthCore\Models\Post  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $video)
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
            'page_subtitle' => 'Edit Video',
            'data' => $video,
        ];

        return view('zetthcore::AdminSC.content.videos_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Post  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Post $video)
    {
        /* validation */
        $this->validate($r, [
            'title' => 'required|max:100|unique:posts,title,' . $video->id . ',id,type,video',
            // 'slug' => 'unique:posts,slug,' . $video->id . ',id,type,video',
            'content' => 'required',
        ]);

        /* save data */
        $video->title = $r->input('title');
        // $video->slug = $slug;
        $video->content = $r->input('content');
        $video->excerpt = substr(strip_tags($video->content), 0, 255);
        $video->type = 'video';
        $video->cover = $r->input('cover');
        $video->status = $r->input('status') ?? 'inactive';
        $video->updated_by = app('user')->id;
        $video->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui video "' . $video->title . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Video "' . $video->title . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Post  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $video)
    {
        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus video "' . $video->title . '"');

        /* soft delete */
        $video->delete();

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Video "' . $video->title . '" berhasil dihapus!');
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
        $data = Post::select('id', 'cover', 'title', 'slug', 'status')->where('type', 'video')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }

}
