<?php

namespace ZetthCore\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Support\Facades\File;

class AdminController extends BaseController
{
    use \ZetthCore\Traits\MainTrait;

    public $isAdminSubdomain = false;
    public $adminPath = '/admin';
    public $breadcrumbs;

    public function __construct()
    {
        $host = parse_url(url('/'))['host'];
        if (strpos($host, 'admin') !== false) {
            $this->isAdminSubdomain = true;
            $this->adminPath = '';
        }
        $this->breadcrumbs[] = [
            'page' => '',
            'icon' => 'fa fa-home',
            'url' => url($this->adminPath),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = str_start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = base_path('vendor/zafranf/zetthcore/src/resources/themes' . $path);
        if (File::exists($path)) {
            $mime = '';
            if (ends_with($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (ends_with($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        abort(404);
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
}
