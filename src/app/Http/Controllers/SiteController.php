<?php

namespace ZetthCore\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use OpenGraph;
use SEOMeta;
use Twitter;

class SiteController extends Controller
{
    use \ZetthCore\Traits\MainTrait;

    public $breacrumbs;

    public function __construct()
    {
        $this->breadcrumbs[] = [
            'page' => 'Beranda',
            'icon' => '',
            'url' => url('/'),
        ];
    }

    public function themes(\Illuminate\Http\Request $r, $path)
    {
        $path = str_start(str_replace(['../', './'], '', urldecode($path)), '/');
        $path = resource_path('themes' . $path);
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

    public function setSEO($title = '', $par = [])
    {
        $separator = config('seotools.meta.defaults.separator');
        $url = url()->current();
        $sitename = app('site')->name;
        $title = !empty($title) ? $title . $separator . $sitename : $sitename;
        $tagline = app('site')->tagline;
        $keywords = app('site')->keyword;
        $description = app('site')->description;
        $logo = _get_image("assets/images/" . app('site')->icon);

        /* Set General SEO */
        SEOMeta::setTitle($title);
        if ($keywords) {
            SEOMeta::setKeywords($keywords);
        }
        if ($description) {
            SEOMeta::setDescription($description);
        }

        /* Set OpenGraph SEO */
        OpenGraph::setTitle($title);
        if ($description) {
            OpenGraph::setDescription($description);
        }
        OpenGraph::setType('website');
        OpenGraph::setUrl($url);
        OpenGraph::setSiteName($sitename);
        OpenGraph::addProperty('locale', 'id_ID');

        if (!empty($par)) {
            $type = 'article';
            $cats = [];
            $tags = [];
            $time = $par->published_at != "0000-00-00 00:00:00" ? $par->published_at : $par->created_at;

            foreach ($par->terms as $k => $v) {
                if ($v->type == "tag") {
                    $tags[] = $v->name;
                }
                if ($v->type = "category") {
                    $cats[] = $v->name;
                }
            }

            $title = $par->title . $separator . $sitename;
            $image = _get_image($par->cover);
            $keywords = implode(',', $tags);
            $description = str_limit(strip_tags($par->content), 300);
            if (strlen($par->excerpt) > 0) {
                $description = strip_tags($par->excerpt);
            }

            /* Set General SEO */
            SEOMeta::setTitle($title);
            if ($keywords) {
                SEOMeta::setKeywords($keywords);
            }
            if ($description) {
                SEOMeta::setDescription($description);
            }

            /* Set OpenGraph SEO */
            OpenGraph::setTitle($title);
            if ($description) {
                OpenGraph::setDescription($description);
            }
            OpenGraph::setType($type);
            OpenGraph::addImage($image);
            OpenGraph::setArticle([
                'published_time' => date('c', strtotime($time)),
                'author' => $par->author->fullname,
                'section' => $cats[0] ?? '',
                'tag' => $tags ?? '',
            ]);

            $socmed = \ZetthCore\Models\SocmedData::where('type', 'site')->with('socmed')->get();
            $account = '';
            foreach ($socmed as $key => $val) {
                if ($val->socmed->name == "Twitter") {
                    $account = $val->username;
                }
            }

            if (!empty($account)) {
                /* Set Twitter SEO */
                Twitter::addValue('card', 'summary');
                Twitter::setImage($image);
                Twitter::setType($type);
                Twitter::setTitle($title);
                Twitter::setSite($account);
                Twitter::setDescription($description);
                Twitter::setUrl($url);
            }
        } else {
            OpenGraph::addImage($logo);
        }
    }
}