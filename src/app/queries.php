<?php
function _doGetData($model, $search = '', $type = '', $with = [], $limit = null, $order = 'desc', $complete = false)
{
    /* check if model is post */
    $model_ex = explode('\\', $model);
    $model_name = end($model_ex);
    $is_posts = ucfirst($model_name) == 'Post';

    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache */
    $cache_name = '_get' . str_slug($model) . $search . $type . implode('', $with) . $limit . $order . $page;
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    $data = $model::active();

    /* query search */
    if (!empty($search)) {
        if (in_array($model_name, ['Post', 'Banner'])) {
            $data->where('title', 'like', '%' . $search . '%');

            if ($is_posts) {
                $data->orWhere('slug', $search);
            }
        } else {
            $data->where('name', 'like', '%' . $search . '%')->orWhere('slug', $search);
        }
    }

    /* check complete params */
    if ($is_posts) {
        if ($type == 'article') {
            $data->posts();
        } else if ($type == 'page') {
            $data->pages();
        } else if ($type == 'video') {
            $data->videos();
        }

        if ($complete) {
            $data->with('comments', 'categories', 'tags', 'author', 'editor');
        } else {
            $data->with('categories', 'author');
            $data->withCount('comments');
        }
    } else if ($model_name == 'Term') {
        $data->where('type', $type);
    }

    /* check relation */
    if (!empty($with)) {
        $data->with($with);
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $data->random();
    } else {
        if ($is_posts) {
            $data->orderBy('published_at', $order);
        } else {
            $data->orderBy('created_at', $order);
        }
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
    return _doGetData(\ZetthCore\Models\Banner::class, '', '', [], $limit);
}

function _getPost($slug = '', $type = 'simple')
{
    return _getPosts($type, 1, 'desc', $slug);
}

function _getPosts($type = 'simple', $limit = null, $order = "desc", $slug = '')
{
    /* check it's complete request */
    $complete = $type == 'complete';

    return _doGetData(\ZetthCore\Models\Post::class, $slug, 'article', [], $limit, $order, $complete);
}

function _getPostsSimple($limit = null, $order = "desc")
{
    return _getPosts('simple', $limit, $order);
}

function _getPostsComplete($limit = null, $order = "desc")
{
    return _getPosts('complete', $limit, $order);
}

function _getCategoryPosts($slug = '', $limit = null, $order = 'desc')
{

}

function _getTerms($type = 'category', $limit = null, $order = 'desc', $slug = '')
{
    return _doGetData(\ZetthCore\Models\Term::class, $slug, $type, [], $limit, $order);
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
    return _getTerms('tags', $limit, $order);
}

function getPage($slug = '')
{
    return _getPages(1, 'desc', $slug);
}

function _getPages($limit = null, $order = 'desc', $slug = '')
{
    return _doGetData(\ZetthCore\Models\Post::class, $slug, 'page', [], $limit, $order);
}

function _getAlbum()
{
    return _getAlbums(1);
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
    $albums = \ZetthCore\Models\Album::where('status', 1);
    $albums->with('photo');
    $albums->withCount('photos');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $albums->random();
    } else {
        $albums->orderBy('created_at', $order);
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
    $photos = \ZetthCore\Models\AlbumDetail::with('album');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $photos->random();
    } else {
        $photos->orderBy('created_at', $order);
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
    Cache::put($cache_name, $photos, $cache_time);

    return $photos;
}

function _getVideo()
{
    return _getVideo(1);
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
    $videos = \ZetthCore\Models\Post::videos()->active();

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $videos->random();
    } else {
        $videos->orderBy('created_at', $order);
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
    Cache::put($cache_name, $videos, $cache_time);

    return $videos;
}
