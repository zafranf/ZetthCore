<?php
function _getBanners($limit = 3, $active = 1)
{
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
    } else {
        $banners = $banners->first();
    }

    /* simpan ke cache */
    Cache::put('_getBanners' . $limit . $active, $banners, 10 * 60);

    return $banners;
}

function _getPosts($pars = '', $limit = 10, $active = 1, $order = "desc")
{
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
    } else {
        $posts = $posts->first();
    }

    /* simpan ke cache */
    Cache::put('_getArticles' . $pars . $limit . $active . $order, $posts, 10 * 60);

    return $posts;
}

function _getTerms($type = 'category', $name = '', $limit = 5, $active = 1, $order_by = 'display_name', $order_sort = 'asc')
{
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
    } else {
        $terms = $terms->first();
    }

    /* simpan ke cache */
    Cache::put('_getTerms' . $name . $type . $limit . $active, $terms, 10 * 60);

    return $terms;
}

function _getPages($limit = 5, $active = 1)
{
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
    } else {
        $pages = $pages->first();
    }

    /* simpan ke cache */
    Cache::put('_getPages' . $limit . $active, $pages, 10 * 60);

    return $pages;
}

function _getAlbums($name = '', $limit = 5, $active = 1)
{
    $page = \Request::input('page') ? \Request::input('page') : 1;

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
    } else {
        $albums = $albums->first();
    }

    /* simpan ke cache */
    Cache::put('_getAlbums' . $name . $limit . $active . $page, $albums, 10 * 60);

    return $albums;
}

function _getPhotos($limit = 5, $active = 1)
{
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
    } else {
        $photos = $photos->first();
    }

    /* simpan ke cache */
    Cache::put('_getPhotos' . $limit . $active, $photos, 10 * 60);

    return $photos;
}

function _getVideos($limit = 5, $active = 1)
{
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
    } else {
        $videos = $videos->first();
    }

    /* simpan ke cache */
    Cache::put('_getVideos' . $limit . $active, $videos, 10 * 60);

    return $videos;
}