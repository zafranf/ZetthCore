<?php

namespace ZetthCore\Http\Controllers\Content;

use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;
use ZetthCore\Models\Post;
use ZetthCore\Models\PostTerm;
use ZetthCore\Models\Term;

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
        $this->current_url = url($this->adminPath . '/content/posts');
        $this->page_title = 'Kelola Artikel';
        $this->breadcrumbs[] = [
            'page' => 'Konten',
            'icon' => '',
            'url' => url($this->adminPath . '/content/banners'),
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

        /* delete post relation */
        PostTerm::where('post_id', $post->id)->delete();

        /* processing categories */
        $this->process_categories($categories, $descriptions, $parents, $post->id);

        /* processing tags */
        $this->process_tags($tags, $post->id);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menambahkan Artikel "' . $post->title . '"');

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
        $this->breadcrumbs[] = [
            'page' => 'Edit',
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
        if ($r->input('cover')) {
            $post->cover = $cover;
        }
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

        /* delete post relation */
        PostTerm::where('post_id', $post->id)->delete();

        /* processing categories */
        $this->process_categories($categories, $descriptions, $parents, $post->id);

        /* processing tags */
        $this->process_tags($tags, $post->id);

        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> memperbarui Artikel "' . $post->title . '"');

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
        /* log aktifitas */
        $this->activityLog('<b>' . \Auth::user()->fullname . '</b> menghapus Artikel "' . $post->title . '"');

        /* soft delete */
        $post->delete();

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
        $data = Post::select('id', 'title', 'slug', 'status')->where('type', 'article')->get();

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($r, $data);
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
        $postrel = new PostTerm;
        $postrel->post_id = $pid;
        $postrel->term_id = $tid;
        $postrel->save();
    }

}
