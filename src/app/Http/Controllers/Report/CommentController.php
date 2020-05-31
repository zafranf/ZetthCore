<?php

namespace ZetthCore\Http\Controllers\Report;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;

class CommentController extends AdminController
{
    private $current_url;
    private $page_title;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->current_url = _url(adminPath() . '/report/comments');
        $this->page_title = 'Kelola Komentar';
        $this->breadcrumbs[] = [
            'page' => 'Laporan',
            'icon' => '',
            'url' => _url(adminPath() . '/report/inbox'),
        ];
        $this->breadcrumbs[] = [
            'page' => 'Komentar',
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
            'page_subtitle' => 'Daftar Komentar',
        ];

        return view('zetthcore::AdminSC.report.comment', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $r)
    {
        $this->breadcrumbs[] = [
            'page' => 'Balas Komentar',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Balas Komentar',
        ];

        /* check comment id */
        if ($r->input('cid')) {
            $data['reply'] = Comment::with('commentator')->find($r->input('cid'));
        } else {
            abort(404);
        }

        return view('zetthcore::AdminSC.report.comment_form', $data);
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
            'content' => 'required',
        ]);

        /* get post */
        $post = Post::find($r->input('pid'));
        if (!$post) {
            return redirect()->back()->withErrors([
                'Data artikel tidak ditemukan',
            ]);
        }

        /* get parent id */
        $parent = Comment::with('commentator')->find($r->input('cid'));
        if (!$parent) {
            return redirect()->back()->withErrors([
                'Data komentar sumber tidak ditemukan',
            ]);
        }

        /* save data */
        $comment = new Comment;
        $comment->name = \Auth::user()->fullname;
        $comment->email = \Auth::user()->email;
        $comment->content = $r->input('content');
        $comment->notify = 'no';
        $comment->read = 'yes';
        $comment->status = 'active';
        $comment->is_owner = 'yes';
        $comment->parent_id = $parent->parent_id ?? $parent->id;
        $comment->commentable_type = $parent->commentable_type;
        $comment->commentable_id = $r->input('pid');
        $comment->approved_by = \Auth::user()->id;
        $comment->created_by = \Auth::user()->id;
        $comment->site_id = app('site')->id;
        $comment->save();

        /* set approved */
        if ($r->input('status') == 'active') {
            if (is_null($parent->approved_by)) {
                $parent->status = 'active';
                $parent->approved_by = $r->input('status') == 'active' ? \Auth::user()->id : null;
                $parent->save();
            }
        }

        /* send notif to commentator */
        $topparent = Comment::find($parent->parent_id ?? $parent->id);
        $data = [
            'post' => $post,
            'parent' => $topparent,
            'comment' => $comment,
        ];
        \ZetthCore\Jobs\CommentReply::dispatch($data);

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') membalas komentar dari "' . $parent->email . '"');

        /* clear cache */
        \Cache::forget('_getPostscompletedesc' . $post->slug . '11' . app('site')->id);

        return redirect($this->current_url)->with('success', 'Komentar berhasil dibalas!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \ZetthCore\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $this->breadcrumbs[] = [
            'page' => 'Detail',
            'icon' => '',
            'url' => '',
        ];

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Detail Komentar',
            'data' => $comment,
        ];

        /* mark as read */
        $comment->read = 'yes';
        $comment->save();

        return view('zetthcore::AdminSC.report.comment_detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \ZetthCore\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->breadcrumbs[] = [
            'page' => 'Edit Komentar',
            'icon' => '',
            'url' => '',
        ];

        /* mark as read */
        $comment->read = 'yes';
        $comment->save();

        /* set variable for view */
        $data = [
            'current_url' => $this->current_url,
            'breadcrumbs' => $this->breadcrumbs,
            'page_title' => $this->page_title,
            'page_subtitle' => 'Edit Komentar',
            'data' => $comment,
        ];

        return view('zetthcore::AdminSC.report.comment_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  \ZetthCore\Models\Comment  $inbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, Comment $comment)
    {
        /* validation */
        $this->validate($r, [
            'name' => 'required',
            'content' => 'required',
        ]);

        /* save data */
        $comment->name = $r->input('name');
        $comment->content = $r->input('content');
        $comment->updated_by = \Auth::user()->user_id;
        if (!bool($comment->is_owner) && $r->input('status') == 'active') {
            $comment->status = 'active';
            $comment->approved_by = \Auth::user()->user_id;
        }
        $comment->save();

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') mengubah komentar dari "' . $comment->email . '"');

        return redirect($this->current_url)->with('success', 'Komentar berhasil disimpan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \ZetthCore\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') menghapus Komentar dari "' . $comment->email . '"');

        /* soft delete */
        $comment->delete();

        return redirect($this->current_url)->with('success', 'Komentar berhasil dihapus!');
    }

    /**
     * Generate DataTables
     */
    public function datatable(Request $r)
    {
        /* get data */
        $data = Comment::select('id', 'name', 'email', \DB::raw('substring(ExtractValue(content, "//text()"), 1, 100) as content'), 'status', 'commentable_id as post_id', 'parent_id')->orderBy('created_at', 'desc');

        /* generate datatable */
        if ($r->ajax()) {
            return $this->generateDataTable($data);
        }

        abort(403);
    }
}
