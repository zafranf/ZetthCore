<?php

namespace ZetthCore\Http\Controllers\Content;

use App\Models\Post;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Term;
use ZetthCore\Models\TermData;

class PostController extends AdminController
{
    private $current_url;
    private $page_title;
    private $width;
    private $height;
    private $ratio;
    private $weight;
    private $image_rule;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = url(adminPath() . '/content/posts');
        $this->page_title = 'Kelola Artikel';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url(adminPath() . '/content/banners'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Artikel',
            'icon' => '',
            'url' => $this->current_url,
        ];

        /* validation */
        $ratio = config('site.post.image.ratio') ?? '16:9';
        $this->width = config('site.post.image.dimension.width') ?? 1280;
        $this->height = config('site.post.image.dimension.height') ?? 720;
        $this->ratio = str_replace(':', '/', $ratio);
        $this->weight = config('site.post.image.weight') ?? 256;
        if ($this->weight > 512) {
            $this->weight = 512;
        }
        $this->image_rule = [
            'nullable',
            'image',
            'mimes:jpeg,png,svg,webp',
            'max:' . $this->weight,
            'dimensions:max_width=' . $this->width . ',max_height=' . $this->height . ',ratio:' . $this->ratio,
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

        /* get categories */
        $categories = Term::where('type', 'category')
            ->where('group', 'post')
            ->whereNull('parent_id')
            ->where('status', 'active')
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
            'cover' => $this->image_rule,
        ]);

        /* set variables */
        $categories = $r->input('categories');
        $descriptions = $r->input('descriptions');
        $parents = $r->input('parents');
        $tags = explode(",", $r->input('tags'));
        $date = $r->input('date') ?? carbon()->format("Y-m-d");
        $time = $r->input('time') ?? carbon()->format("H:i:s");
        $digit = 3;
        $uniq = \Str::random($digit);

        /* save data */
        $post = new Post;
        $post->title = $r->input('title');
        $post->slug = $r->input('slug') ?? \Str::slug($post->title);
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt') ?? substr(strip_tags($post->content), 0, 255);
        $post->type = 'article';
        $post->status = $r->input('status') == 'set' ? 'active' : $r->input('status');
        $post->enable_like = $r->input('enable_like') ?? 'no';
        $post->enable_share = $r->input('enable_share') ?? 'no';
        $post->enable_comment = $r->input('enable_comment') ?? 'no';
        $post->published_at = carbon_query($date . ' ' . $time);
        $post->short_url = $uniq;
        $post->created_by = app('user')->id;
        $post->site_id = app('site')->id;
        $post->save();

        /* process image */
        if ($r->hasFile('cover')) {
            $file = $r->file('cover');
            $ext = $file->getClientOriginalExtension();
            $name = 'post-' . (md5($post->id . env('DB_PORT', 3306))) . '.' . $ext;

            if ($this->uploadImage($file, '/assets/images/posts/', $name)) {
                $post->cover = $name;
            }
        }
        $post->caption = $r->input('caption');
        $post->save();

        /* delete post relation */
        TermData::where('termable_id', $post->id)->delete();

        /* processing categories */
        $this->process_categories($categories, $descriptions, $parents, $post->id);

        /* processing tags */
        $this->process_tags($tags, $post->id);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') membuat artikel "' . $post->slug . '"');

        /* notif to subscriber */
        if (app('site')->enable_subscribe && $r->input('info_subscriber')) {
            \ZetthCore\Jobs\NewPost::dispatch($post);
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
            ->where('group', 'post')
            ->whereNull('parent_id')
            ->where('status', 'active')
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
            'content' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        /* set variables */
        $categories = $r->input('categories');
        $descriptions = $r->input('descriptions');
        $parents = $r->input('parents');
        $tags = explode(",", $r->input('tags'));
        $date = $r->input('date') ?? carbon()->format("Y-m-d");
        $time = $r->input('time') ?? carbon()->format("H:i:s");

        /* save data */
        $post->title = $r->input('title');
        $post->content = $r->input('content');
        $post->excerpt = $r->input('excerpt') ?? substr(strip_tags($post->content), 0, 255);
        $post->type = 'article';
        $post->status = $r->input('status') == 'set' ? 'active' : $r->input('status');
        $post->enable_like = $r->input('enable_like') ?? 'no';
        $post->enable_share = $r->input('enable_share') ?? 'no';
        $post->enable_comment = $r->input('enable_comment') ?? 'no';
        $post->published_at = carbon_query($date . ' ' . $time);
        $post->updated_by = app('user')->id;
        $post->save();

        /* process image */
        if ($r->input('cover_remove')) {
            $post->cover = null;
        } else if ($r->hasFile('cover')) {
            $file = $r->file('cover');
            $ext = $file->getClientOriginalExtension();
            $name = 'post-' . (md5($post->id . env('DB_PORT', 3306))) . '.' . $ext;

            if ($this->uploadImage($file, '/assets/images/posts/', $name)) {
                $post->cover = $name;
            }
        }
        $post->caption = $r->input('caption');
        $post->save();

        /* delete post relation */
        TermData::where('termable_id', $post->id)->delete();

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

        /* remove image file */
        // $file = storage_path('app/public/assets/images/posts/' . $post->cover);
        // if (file_exists($file) && !is_dir($file)) {
        //     unlink($file);
        // }

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
        $data = Post::select('id', \DB::raw('CONCAT("/storage/assets/images/posts/", cover) as cover'), 'title', 'slug', 'status', 'visited', 'shared', 'liked', 'created_by')->articles()->with('author', 'comments_all', 'categories')->orderBy('created_at', 'desc');

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
            $chkCategory = Term::where('name', \Str::slug($category))
                ->where('type', 'category')
                ->where('group', 'post')
                ->first();

            if (!$chkCategory) {
                $term = new Term;
                $term->name = $category;
                $term->slug = \Str::slug($term->name);
                $term->description = $descriptions[$k];
                if (isset($parents[$k]) && $parents[$k] != 0) {
                    $term->parent_id = $parents[$k];
                }
                $term->type = 'category';
                $term->group = 'post';
                $term->status = 'active';
                $term->site_id = app('site')->id;
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
            $chkTag = Term::where('name', \Str::slug($tag))
                ->where('type', 'tag')
                ->where('group', 'post')
                ->first();

            if (!$chkTag) {
                $term = new Term;
                $term->name = strtolower($tag);
                $term->slug = \Str::slug($term->name);
                $term->type = 'tag';
                $term->group = 'post';
                $term->status = 'active';
                $term->site_id = app('site')->id;
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
        $postrel = new TermData;
        $postrel->term_id = $tid;
        $postrel->termable_type = 'App\Models\Post';
        $postrel->termable_id = $pid;
        $postrel->site_id = app('site')->id;
        $postrel->save();
    }
}
