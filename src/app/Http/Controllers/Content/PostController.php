<?php

namespace ZetthCore\Http\Controllers\Content;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Post;
use ZetthCore\Models\Term;
use ZetthCore\Models\Termable;

class PostController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(app('admin_path') . '/content/posts');
        $this->page_title = 'Kelola Artikel';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url(app('admin_path') . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Artikel',
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
            'page_subtitle' => 'Daftar Artikel',
        ];

        return view('zetthcore::AdminSC.content.posts', $data);
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

        $categories = Term::where('type', 'category')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->with('subcategory')
            ->get();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'breadcrumbs' => $this->breadcrumbs,
            'page_subtitle' => 'Tambah Artikel',
            'categories' => $categories,
        ];

        return view('zetthcore::AdminSC.content.posts_form', $data);
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
            'title' => 'required|max:100|unique:posts,title,NULL,created_at,type,article,deleted_at,NULL',
            'slug' => 'required|max:100|unique:posts,slug,NULL,created_at,type,article,deleted_at,NULL',
            'content' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        /* set variables */
        // $title = $r->input('title');
        // $slug = str_slug($r->input('slug'));
        $categories = $r->input('categories');
        $descriptions = $r->input('descriptions');
        $parents = $r->input('parents');
        $tags = explode(",", $r->input('tags'));
        // $digit = 3;
        // $uniq = str_random($digit);
        // $cover = str_replace(url('/'), '', $r->input('cover'));
        $date = $r->input('date') ?? carbon()->format("Y-m-d");
        $time = $r->input('time') ?? carbon()->format("H:i:s");

        /* save data */
        $post = new Post;
        $post->title = $r->input('title');
        $post->slug = $r->input('slug') ?? str_slug($post->title);
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt') ?? substr(strip_tags($post->content), 0, 255);
        $post->type = 'article';
        $post->cover = $r->input('cover');
        $post->status = $r->input('status');
        $post->share = ($r->input('share')) ? 1 : 0;
        $post->like = ($r->input('like')) ? 1 : 0;
        $post->comment = ($r->input('comment')) ? 1 : 0;
        $post->published_at = carbon_query($date . ' ' . $time);
        // $post->short_url = $uniq;
        $post->created_by = app('user')->id;
        $post->save();

        /* delete post relation */
        Termable::where('termable_id', $post->id)->delete();

        /* processing categories */
        $this->process_categories($categories, $descriptions, $parents, $post->id);

        /* processing tags */
        $this->process_tags($tags, $post->id);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') membuat artikel "' . $post->slug . '"');

        /* notif to subscriber */
        if (app('site')->enable_subscribe && $r->input('info_subscriber')) {
            $this->sendToSubscriber($post);
        }

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Artikel "' . $post->title . '" berhasil ditambah!');
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
        /* set breadcrumbs */
        $this->breadcrumbs[] = [
            'page' => 'Edit',
            'icon' => '',
            'url' => '',
        ];

        /* get active categories */
        $categories = Term::where('type', 'category')
            ->where('parent_id', 0)
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->with('subcategory')
            ->get();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'page_title' => $this->page_title,
            'breadcrumbs' => $this->breadcrumbs,
            'page_subtitle' => 'Edit Artikel',
            'categories' => $categories,
            'data' => $post->load('terms'),
        ];

        return view('zetthcore::AdminSC.content.posts_form', $data);
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
            'title' => 'required|max:100|unique:posts,title,' . $post->id . ',id,type,article,deleted_at,NULL',
            // 'slug' => 'unique:posts,slug,' . $post->id . ',id,type,article',
            'content' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        /* set variables */
        // $title = $r->input('title');
        // $slug = str_slug($r->input('slug'));
        $categories = $r->input('categories');
        $descriptions = $r->input('descriptions');
        $parents = $r->input('parents');
        $tags = explode(",", $r->input('tags'));
        // $digit = 3;
        // $uniq = str_random($digit);
        // $cover = str_replace(url('/'), '', $r->input('cover'));
        $date = $r->input('date') ?? carbon()->format("Y-m-d");
        $time = $r->input('time') ?? carbon()->format("H:i:s");

        /* save data */
        $post->title = $r->input('title');
        // $post->slug = $slug;
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt') ?? substr(strip_tags($post->content), 0, 255);
        $post->type = 'article';
        if ($r->input('cover')) {
            $post->cover = $r->input('cover');
        }
        if ($r->input('cover_remove')) {
            $post->cover = '';
        }
        $post->status = $r->input('status');
        $post->share = ($r->input('share')) ? 1 : 0;
        $post->like = ($r->input('like')) ? 1 : 0;
        $post->comment = ($r->input('comment')) ? 1 : 0;
        $post->published_at = carbon_query($date . ' ' . $time);
        // $post->short_url = $uniq;
        $post->updated_by = app('user')->id;
        $post->save();

        /* delete post relation */
        Termable::where('termable_id', $post->id)->delete();

        /* processing categories */
        $this->process_categories($categories, $descriptions, $parents, $post->id);

        /* processing tags */
        $this->process_tags($tags, $post->id);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') memperbarui artikel "' . $post->title . '"');

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Artikel "' . $post->title . '" berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus artikel "' . $post->title . '"');

        /* soft delete */
        $post->delete();

        /* clear cache */
        \Cache::flush();

        return redirect($this->current_url)->with('success', 'Artikel "' . $post->title . '" berhasil dihapus!');
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
        $data = Post::/* select('id', 'cover', 'title', 'slug', 'status', 'visited', 'shared', 'liked', 'created_by')-> */articles()->with('author', 'comments_all', 'categories')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }

    /**
     * Undocumented function
     *
     * @param [type] $categories
     * @param [type] $descriptions
     * @param [type] $parents
     * @param [type] $pid
     * @return void
     */
    public function process_categories($categories, $descriptions, $parents, $pid)
    {
        foreach ($categories as $k => $category) {
            $chkCategory = Term::where('name', str_slug($category))
                ->where('type', 'category')
                ->first();

            if (!$chkCategory) {
                $term = new Term;
                $term->name = $category;
                $term->slug = str_slug($term->name);
                $term->description = $descriptions[$k];
                $term->parent_id = $parents[$k] ?? 0;
                $term->type = 'category';
                $term->status = 1;
                $term->save();

                $cid = $term->id;
            } else {
                $cid = $chkCategory->id;
            }

            /* process relations */
            $this->process_postrels($pid, $cid);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $tags
     * @param [type] $pid
     * @return void
     */
    public function process_tags($tags, $pid)
    {
        foreach ($tags as $tag) {
            $chkTag = Term::where('name', str_slug($tag))->
                where('type', 'tag')->
                first();

            if (!$chkTag) {
                $term = new Term;
                $term->name = strtolower($tag);
                $term->slug = str_slug($term->name);
                $term->type = 'tag';
                $term->status = 1;
                $term->save();

                $tid = $term->id;
            } else {
                $tid = $chkTag->id;
            }

            /* process relations */
            $this->process_postrels($pid, $tid);
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $pid
     * @param [type] $tid
     * @return void
     */
    public function process_postrels($pid, $tid)
    {
        $postrel = new Termable;
        $postrel->term_id = $tid;
        $postrel->termable_type = 'App\Models\Post';
        $postrel->termable_id = $pid;
        $postrel->save();
    }

    public function sendToSubscriber(Post $post)
    {
        \ZetthCore\Jobs\NotifSubscriber::dispatch($post);
    }

}
