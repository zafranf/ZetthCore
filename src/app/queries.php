<?php
function _doGetData($cache_name, $data, $limit = null)
{
    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache */
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* cek limit */
    if ($limit > 1) {
        $data = $data->paginate($limit);
    } else if ($limit == 1) {
        $data = $data->first();
    } else {
        $data = $data->get();
    }

    /* simpan ke cache */
    Cache::put($cache_name, $data, $cache_time);

    return $data;
}

function _getBanners($limit = null)
{
    /* inisiasi query */
    $banners = \ZetthCore\Models\Banner::active()->orderBy('order', 'asc');

    return _doGetData('_getBanner' . $limit, $banners, $limit);
}

function _getPost($slug = '', $type = 'complete')
{
    return _getPosts($type, 1, 'desc', $slug);
}

function _getPosts($type = 'simple', $limit = null, $order = "desc", $slug = '')
{
    /* check it's complete request */
    $complete = $type == 'complete';

    /* cache name */
    $cache_name = '_getPosts' . $type . $limit . $order . $slug;

    /* inisiasi query */
    $posts = \ZetthCore\Models\Post::posts()->active();
    if (!empty($slug)) {
        if (in_array($type, ['category', 'tag'])) {
            if ($type == 'category') {
                $posts->withCategory($slug);
            } else if ($type == 'tag') {
                $posts->withTag($slug);
            }
        } else {
            $posts->where('slug', $slug)->orWhere('title', 'like', '%' . $slug . '%');
        }
    }

    /* check complete params */
    if ($complete) {
        $posts->with('comments', 'categories', 'tags', 'author', 'editor');
    } else {
        $posts->with('categories', 'author');
        $posts->withCount('comments');
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $posts->inRandomOrder();
    } else {
        $posts->orderBy('published_at', $order);
    }

    return _doGetData($cache_name, $posts, $limit);
}

function _getPostsSimple($limit = null, $order = "desc")
{
    return _getPosts('simple', $limit, $order);
}

function _getPostsComplete($limit = null, $order = "desc")
{
    return _getPosts('complete', $limit, $order);
}

function _getCategoryPosts($slug, $limit = null, $order = 'desc')
{
    return _getPosts('category', $limit, $order, $slug);
}

function _getTagPosts($slug, $limit = null, $order = 'desc')
{
    return _getPosts('tag', $limit, $order, $slug);
}

function _getTerms($type = 'category', $limit = null, $order = 'desc')
{
    /* cache name */
    $cache_name = '_getTerms' . $type . $limit . $order;

    /* inisiasi query */
    $terms = \ZetthCore\Models\Term::active()->where('type', $type);

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $terms->inRandomOrder();
    } else {
        $terms->orderBy('name', $order);
    }

    return _doGetData($cache_name, $terms, $limit);
}

function _getCategory()
{
    return _getCategories(1);
}

function _getCategories($limit = null, $order = 'desc')
{
    return _getTerms('categories', $limit, $order);
}

function _getTag()
{
    return _getTags(1);
}

function _getTags($limit = null, $order = 'desc')
{
    return _getTerms('tag', $limit, $order);
}

function getPage($slug = '')
{
    return _getPages(1, 'desc', $slug);
}

function _getPages($limit = null, $order = 'desc', $slug = '')
{
    /* cache name */
    $cache_name = '_getPages' . $limit . $order . $slug;

    /* inisiasi query */
    $pages = \ZetthCore\Models\Post::pages()->active();
    if (!empty($slug)) {
        $pages->where('slug', $slug)->orWhere('title', 'like', '%' . $slug . '%');
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $pages->inRandomOrder();
    } else {
        $pages->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $pages, $limit);
}

function _getAlbum($slug = '')
{
    return _getAlbums(1, 'desc', $slug);
}

function _getAlbums($limit = null, $order = 'desc', $slug = '')
{
    /* cache name */
    $cache_name = '_getAlbums' . $limit . $order . $slug;

    /* inisiasi query */
    $albums = \ZetthCore\Models\Album::where('status', 1);
    if (!empty($slug)) {
        $albums->where('slug', $slug)->orWhere('name', 'like', '%' . $slug . '%');
    }

    /* relation */
    $albums->with('photo');
    $albums->withCount('photos');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $albums->inRandomOrder();
    } else {
        $albums->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $albums, $limit);
}

function _getPhotos($limit = null, $order = 'desc')
{
    /* cache name */
    $cache_name = '_getPhotos' . $limit . $order;

    /* inisiasi query */
    $photos = \ZetthCore\Models\AlbumDetail::with('album');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $photos->inRandomOrder();
    } else {
        $photos->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $photos, $limit);
}

function _getVideo($slug = '')
{
    return _getVideo(1, 'desc', $slug);
}

function _getVideos($limit = null, $order = 'desc', $slug = '')
{
    /* cache name */
    $cache_name = '_getVideos' . $limit . $order . $slug;

    /* inisiasi query */
    $videos = \ZetthCore\Models\Post::videos()->active();

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $videos->inRandomOrder();
    } else {
        $videos->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $videos, $limit);
}
