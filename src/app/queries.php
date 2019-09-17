<?php
function _getBanners($limit = null, $active = 1)
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getBanners' . $limit . $active);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $banners = \ZetthCore\Models\Banner::orderBy('order');

    /* cek status */
    if ($active != "all") {
        $banners->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $banners = $banners->paginate($limit);
    } else if ($limit == 1) {
        $banners = $banners->first();
    } else {
        $banners = $banners->get();
    }

    /* simpan ke cache */
    Cache::put('_getBanners' . $limit . $active, $banners, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $banners;
}

function _getPosts($pars = '', $limit = null, $active = 1, $order = "desc")
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getArticles' . $pars . $limit . $active . $order);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $posts = \ZetthCore\Models\Post::articles()->orderBy('published_at', $order);

    /* pisah parameter */
    $params = explode('|', $pars);
    foreach ($params as $par) {
        /* cek parameter kategori */
        if (str_contains($par, "categories")) {
            $posts->with('categories');

            /* proses kategori tertentu */
            $cats = explode('=', $par);
            if (count($cats) > 1) {
                $cats = explode(',', $cats[1]);
                $cats = array_map(function ($v) {
                    return str_slug($v);
                }, $cats);

                $posts->withCategories($cats);
            }
        }

        /* cek parameter tags */
        if (str_contains($par, "tags")) {
            $posts->with('tags');

            /* proses tag tertentu */
            $tags = explode('=', $par);
            if (count($tags) > 1) {
                $tags = explode(',', $tags[1]);
                $tags = array_map(function ($v) {
                    return str_slug($v);
                }, $tags);

                $posts->withTags($tags);
            }
        }

        /* cek parameter komen */
        if (str_contains($par, "comments")) {
            if (str_contains($par, "_count")) {
                $posts->withCount('comments');
            } else {
                $posts->with('comments');
            }
        }

        /* cek parameter author */
        if (str_contains($par, "author")) {
            $posts->with('author');
        }

        /* cek parameter editor */
        if (str_contains($par, "editor")) {
            $posts->with('editor');
        }
    }

    /* cek status */
    if ($active != "all") {
        $posts->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $posts = $posts->paginate($limit);
    } else if ($limit == 1) {
        $posts = $posts->first();
    } else {
        $posts = $posts->get();
    }

    /* simpan ke cache */
    Cache::put('_getArticles' . $pars . $limit . $active . $order, $posts, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $posts;
}

function _getTerms($type = 'category', $name = '', $limit = null, $active = 1, $order_by = 'display_name', $order_sort = 'asc')
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getTerms' . $name . $type . $limit . $active);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $terms = \ZetthCore\Models\Term::orderBy($order_by, $order_sort);

    /* cek nama term */
    if ($name != '') {
        $terms->where('slug', $name);
    }
    /* cek type */
    if ($type != '') {
        $terms->where('type', $type);
    }

    /* cek status */
    if ($active != "all") {
        $terms->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $terms = $terms->paginate($limit);
    } else if ($limit == 1) {
        $terms = $terms->first();
    } else {
        $terms = $terms->get();
    }

    /* simpan ke cache */
    Cache::put('_getTerms' . $name . $type . $limit . $active, $terms, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $terms;
}

function _getPages($limit = null, $active = 1)
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getPages' . $limit . $active);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $pages = \ZetthCore\Models\Post::pages()->orderBy('post_id', 'DESC');

    /* cek status */
    if ($active != "all") {
        $pages->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $pages = $pages->paginate($limit);
    } else if ($limit == 1) {
        $pages = $pages->first();
    } else {
        $pages = $pages->get();
    }

    /* simpan ke cache */
    Cache::put('_getPages' . $limit . $active, $pages, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $pages;
}

function _getAlbums($name = '', $limit = null, $active = 1)
{
    $page = \Request::input('page') ? \Request::input('page') : 1;

    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getAlbums' . $name . $limit . $active . $page);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $albums = \ZetthCore\Models\Album::orderBy('id', 'DESC');

    /* cek nama album */
    if ($name != '') {
        $albums->where('slug', $name);
        $albums->with('photos');
    } else {
        $albums->with('photo');
    }

    /* cek status */
    if ($active != "all") {
        $albums->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $albums = $albums->paginate($limit);
    } else if ($limit == 1) {
        $albums = $albums->first();
    } else {
        $albums = $albums->get();
    }

    /* simpan ke cache */
    Cache::put('_getAlbums' . $name . $limit . $active . $page, $albums, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $albums;
}

function _getPhotos($limit = null, $active = 1)
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getPhotos' . $limit . $active);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $photos = \ZetthCore\Models\AlbumPhoto::orderBy('id', 'DESC');

    /* cek status */
    if ($active != "all") {
        $photos->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $photos = $photos->paginate($limit);
    } else if ($limit == 1) {
        $photos = $photos->first();
    } else {
        $photos = $photos->get();
    }

    /* simpan ke cache */
    Cache::put('_getPhotos' . $limit . $active, $photos, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $photos;
}

function _getVideos($limit = null, $active = 1)
{
    /* set limit */
    $limit = $limit ?? app('setting')->perpage;

    /* cek cache */
    $cache = Cache::get('_getVideos' . $limit . $active);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $videos = \ZetthCore\Models\Post::videos()->orderBy('published_at', 'DESC');

    /* cek status */
    if ($active != "all") {
        $videos->where('status', $active);
    }

    /* cek limit */
    if ($limit > 1) {
        $videos = $videos->paginate($limit);
    } else if ($limit == 1) {
        $videos = $videos->first();
    } else {
        $videos = $videos->get();
    }

    /* simpan ke cache */
    Cache::put('_getVideos' . $limit . $active, $videos, 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10)));

    return $videos;
}
