<?php

namespace ZetthCore\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class AdminController extends BaseController
{
    use \ZetthCore\Traits\MainTrait;

    public $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'page' => '',
            'icon' => 'fa fa-home',
            'url' => adminPath(),
        ];
    }

    public function themes(Request $r, $path)
    {
        if (strpos($path, 'AdminSC') !== false) {
            $path = \Str::start(str_replace(['../', './', 'admin'], '', urldecode($path)), '/');
            $path = base_path('vendor/zafranf/zetthcore/src/resources/themes' . $path);
        } else {
            $path = \Str::start(str_replace(['../', './'], '', urldecode($path)), '/');
            $path = resource_path('themes' . $path);
        }

        return $this->getThemeFiles($path);
    }

    public function getLarafile($path)
    {
        $path = base_path('vendor/zafranf/zetthcore/src/resources/assets/filemanager/' . $path);

        return $this->getThemeFiles($path);
    }

    public function getAdditionalDataOpts()
    {
        /* get banners */
        $banners = \ZetthCore\Models\Banner::select('id', 'order', 'title')->orderBy('order')->get();

        /* get ~30 posts */
        $pages = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'page',
            'status' => 'active',
        ])->orderBy('published_at', 'desc')->take(10)->get();
        $articles = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'article',
            'status' => 'active',
        ])->orderBy('published_at', 'desc')->take(10)->get();
        $videos = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'video',
            'status' => 'active',
        ])->orderBy('published_at', 'desc')->take(10)->get();

        /* merge posts */
        $posts = collect();
        $posts = $posts->merge($pages);
        $posts = $posts->merge($articles);
        $posts = $posts->merge($videos);

        return [
            'banners' => $banners,
            'posts' => $posts,
        ];
    }

    public function saveDetail($user, $r)
    {
        /* save user detail */
        $detail = \ZetthCore\Models\UserDetail::updateOrCreate([
            'user_id' => $user->id,
            'site_id' => app('user')->id,
        ], [
            'about' => $r->input('about'),
        ]);

        /* save socmed */
        $this->saveSocmed($user, $r);
    }

    /**
     * Save user's social media
     */
    public function saveSocmed($user, Request $r)
    {
        /* processing socmed */
        $del = \ZetthCore\Models\SocmedData::where([
            'socmedable_type' => 'App\Models\User',
            'socmedable_id' => $user->id,
        ])->forceDelete();
        foreach ($r->input('socmed_id') as $key => $val) {
            if ($r->input('socmed_id')[$key] != "" && $r->input('socmed_uname')[$key] != "") {
                $socmed = new \ZetthCore\Models\SocmedData;
                $socmed->username = $r->input('socmed_uname')[$key];
                $socmed->socmed_id = $r->input('socmed_id')[$key];
                $socmed->socmedable_type = 'App\Models\User';
                $socmed->socmedable_id = $user->id;
                $socmed->save();
            }
        }
    }

    public function getUserRoles()
    {
        return implode(',', \Auth::user()->getRoles());
    }

    public function commentApproval(Request $r, $type, $comment_id)
    {
        /* get comment */
        $comment = \App\Models\Comment::with('commentator')->find($comment_id);
        if (!$comment) {
            abort(404);
        }
        if ($type == 'approve') {
            $comment->status = 'active';
            $comment->approved_by = \Auth::user()->id;

            /* text */
            $log_text = '[~name] (' . $this->getUserRoles() . ') menyetujui komentar dari ' . $comment->commentator->fullname;
            $success_text = 'Komentar berhasil disetujui!';
        } else if ($type == 'unapprove') {
            $comment->status = 'inactive';
            $comment->approved_by = null;

            /* text */
            $log_text = '[~name] (' . $this->getUserRoles() . ') membatalkan persetujuan komentar dari ' . $comment->commentator->fullname;
            $success_text = 'Komentar berhasil batal disetujui!';
        }
        $comment->save();

        /* save activity */
        $this->activityLog($log_text);

        return redirect()->back()->with('success', $success_text);
    }
}
