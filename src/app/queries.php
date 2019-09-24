<?php
function _getBanners($limit = null)
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache */
    $cache_name = '_getBanners' . $limit . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $banners = \ZetthCore\Models\Banner::where('status', 1)->orderBy('order', 'asc');

    /* cek limit */
    if ($limit > 1) {
        $banners = $banners->paginate($limit);
    } else if ($limit == 1) {
        $banners = $banners->first();
    } else {
        $banners = $banners->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $banners, $cache_time);

    return $banners;
}

function _getPosts($type = 'simple', $limit = null, $order = "desc")
{
    /* check is it's complete request */
    $complete = $type == 'complete';

    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache */
    $cache_name = '_getPosts' . $type . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $posts = \ZetthCore\Models\Post::posts()->active()->orderBy('published_at', $order);

    /* check complete params */
    if ($complete) {
        $posts->with('comments', 'categories', 'tags', 'author', 'editor');
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
    Cache::put($cache_name, $posts, $cache_time);

    return $posts;
}

function _getPostsSimple($limit = null, $order = "desc")
{
    return _getPosts('simple', $limit, $order);
}

function _getPostsComplete($limit = null, $order = "desc")
{
    return _getPosts('complete', $limit, $order);
}

function _getTerms($type = 'category', $limit = null, $order = 'desc')
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache time */
    $cache_name = '_getTerms' . $type . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $terms = \ZetthCore\Models\Term::where('type', $type)->where('status', 1)->orderBy('name', $order);

    /* cek limit */
    if ($limit > 1) {
        $terms = $terms->paginate($limit);
    } else if ($limit == 1) {
        $terms = $terms->first();
    } else {
        $terms = $terms->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $terms, $cache_time);

    return $terms;
}

function _getCategories($limit = null, $order = 'desc')
{
    return _getTerms('categories', $limit, $order);
}

function _getTags($limit = null, $order = 'desc')
{
    return _getTerms('tags', $limit, $order);
}

function _getPages($limit = null, $order = 'desc')
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache time */
    $cache_name = '_getPages' . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $pages = \ZetthCore\Models\Post::pages()->active()->orderBy('created_at', $order);

    /* cek limit */
    if ($limit > 1) {
        $pages = $pages->paginate($limit);
    } else if ($limit == 1) {
        $pages = $pages->first();
    } else {
        $pages = $pages->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $pages, $cache_time);

    return $pages;
}

function _getAlbums($limit = null, $order = 'desc')
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache time */
    $cache_name = '_getAlbums' . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $albums = \ZetthCore\Models\Album::where('status', 1)->orderBy('created_at', $order);
    $albums->with('photo');

    /* cek limit */
    if ($limit > 1) {
        $albums = $albums->paginate($limit);
    } else if ($limit == 1) {
        $albums = $albums->first();
    } else {
        $albums = $albums->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $albums, $cache_time);

    return $albums;
}

function _getPhotos($limit = null, $order = 'desc')
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache time */
    $cache_name = '_getPhotos' . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $albums = \ZetthCore\Models\AlbumDetail::orderBy('created_at', $order);

    /* cek limit */
    if ($limit > 1) {
        $albums = $albums->paginate($limit);
    } else if ($limit == 1) {
        $albums = $albums->first();
    } else {
        $albums = $albums->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $albums, $cache_time);

    return $albums;
}

function _getVideos($limit = null, $order = 'desc')
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache time */
    $cache_name = '_getVideos' . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $videos = \ZetthCore\Models\Post::videos()->where('status', 1)->orderBy('published_at', $order);

    /* cek limit */
    if ($limit > 1) {
        $videos = $videos->paginate($limit);
    } else if ($limit == 1) {
        $videos = $videos->first();
    } else {
        $videos = $videos->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $videos, $cache_time);

    return $videos;
}

/* include cms queries */
if (file_exists(app_path('queries.php'))) {
    include app_path('queries.php');
}
