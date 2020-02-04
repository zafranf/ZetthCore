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
            'url' => url(app('admin_path')),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = \Str::start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = base_path('vendor/zafranf/zetthcore/src/resources/themes' . $path);

        return $this->getThemeFiles($path);
    }

    public function larafile($path)
    {
        $path = storage_path('app/public/filemanager/' . $path);

        return $this->getThemeFiles($path);
    }

    public function getAdditionalDataOpts()
    {
        /* get banners */
        $banners = \ZetthCore\Models\Banner::select('id', 'order', 'title')->orderBy('order')->get();

        /* get ~30 posts */
        $pages = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'page',
            'status' => 1,
        ])->orderBy('published_at', 'desc')->take(10)->get();
        $articles = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'article',
            'status' => 1,
        ])->orderBy('published_at', 'desc')->take(10)->get();
        $videos = \ZetthCore\Models\Post::select('type', 'slug', 'title')->where([
            'type' => 'video',
            'status' => 1,
        ])->orderBy('published_at', 'desc')->take(10)->get();
        $posts = collect();
        $posts = $posts->merge($pages);
        $posts = $posts->merge($articles);
        $posts = $posts->merge($videos);

        return [
            'banners' => $banners,
            'posts' => $posts,
        ];
    }

    /**
     * Save user's social media
     */
    public function saveSocmed($user, Request $r)
    {
        /* processing socmed */
        $del = \ZetthCore\Models\SocmedData::where([
            'type' => 'user',
            'data_id' => $user->id,
        ])->forceDelete();
        foreach ($r->input('socmed_id') as $key => $val) {
            if ($r->input('socmed_id')[$key] != "" && $r->input('socmed_uname')[$key] != "") {
                $socmed = new \ZetthCore\Models\SocmedData;
                $socmed->username = $r->input('socmed_uname')[$key];
                $socmed->type = 'user';
                $socmed->socmed_id = $r->input('socmed_id')[$key];
                $socmed->data_id = $user->id;
                $socmed->save();
            }
        }
    }
}
