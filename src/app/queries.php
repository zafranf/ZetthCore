<?php
function _doGetData(array $params)
{
    /* set parameters */
    $default_params = [
        'model' => '',
        'search' => '',
        'type' => '',
        'with' => [],
        'with_count' => [],
        'limit' => null,
        'order' => 'desc',
        'complete' => false,
        'status' => 1,
    ];
    $params = array_merge($default_params, $params);

    /* check if model is post */
    $model_ex = explode('\\', $params['model']);
    $model_name = end($model_ex);
    $is_posts = $model_name == 'Post';

    /* set page limit */
    $page = \Request::input('page') ? \Request::input('page') : 1;
    $limit = $params['limit'] ?? app('site')->perpage;

    /* set params string for cache name*/
    $params_string = $params;
    $params_string['with'] = implode('.', $params['with']);
    $params_string['with_count'] = implode('.', $params['with_count']);
    $params_string['limit'] = $limit;
    $params_string['page'] = $page;

    /* set cache */
    $cache_name = '_getData.' . implode('.', $params_string);
    $cache_time = 60 * (env('APP_ENV') != 'production' ? 1 : env('CACHE_TIME', 10));

    /* cek cache */
    $cache = Cache::get($cache_name);
    if ($cache) {
        return $cache;
    }

    /* inisiasi query */
    if ($params['status']) {
        $data = $params['model']::active();
    } else {
        $data = new $params['model'];
    }

    /* query search */
    if (isset($params['search']) && !empty($params['search'])) {
        if (in_array($model_name, ['Post', 'Banner'])) {
            $data->where('title', 'like', '%' . $params['search'] . '%');

            if ($is_posts) {
                $data->orWhere('slug', $params['search']);
            }
        } else {
            $data->where('name', 'like', '%' . $params['search'] . '%')->orWhere('slug', $params['search']);
        }
    }

    /* check complete params */
    if ($is_posts) {
        if ($params['type'] == 'article') {
            $data->posts();
        } else if ($params['type'] == 'page') {
            $data->pages();
        } else if ($params['type'] == 'video') {
            $data->videos();
        }

        if (isset($params['complete']) && bool($params['complete'])) {
            $data->with('comments', 'categories', 'tags', 'author', 'editor');
        } else {
            $data->with('categories', 'author');
            $data->withCount('comments');
        }
    } else if ($model_name == 'Term') {
        $data->where('type', $params['type']);
    }

    /* check relation */
    if (isset($params['with']) && !empty($params['with'])) {
        $data->with($params['with']);
    }
    if (isset($params['with_count']) && !empty($params['with_count'])) {
        $data->withCount($params['with_count']);
    }

    /* check order */
    if (isset($params['order'])) {
        if (in_array($params['order'], ['rand', 'random'])) {
            $data->random();
        } else {
            if ($is_posts) {
                $data->orderBy('published_at', $params['order']);
            } else if ($model_name == 'Banner') {
                $data->orderBy('order', 'asc');
            } else {
                $data->orderBy('created_at', $params['order']);
            }
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
    return _doGetData([
        'model' => \ZetthCore\Models\Banner::class,
        'limit' => $limit,
    ]);
}

function _getPost($slug = '', $type = 'simple')
{
    return _getPosts($type, 1, 'desc', $slug);
}

function _getPosts($limit = null, $order = "desc", $complete = false, $slug = '')
{
    return _doGetData([
        'model' => \ZetthCore\Models\Post::class,
        'slug' => $slug,
        'type' => 'article',
        'limit' => $limit,
        'order' => $order,
        'complete' => $complete,
    ]);
}

function _getPostsSimple($limit = null, $order = "desc")
{
    return _getPosts($limit, $order, false);
}

function _getPostsComplete($limit = null, $order = "desc")
{
    return _getPosts($limit, $order, true);
}

function _getCategoryPosts($slug = '', $limit = null, $order = 'desc')
{

}

function _getTerms($type = 'category', $limit = null, $order = 'desc', $slug = '')
{
    return _doGetData([
        'model' => \ZetthCore\Models\Term::class,
        'slug' => $slug,
        'type' => $type,
        'limit' => $limit,
        'order' => $order,
    ]);
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
    return _doGetData([
        'model' => \ZetthCore\Models\Post::class,
        'slug' => $slug,
        'type' => 'page',
        'limit' => $limit,
        'order' => $order,
    ]);
}

function _getAlbum($slug = '')
{
    return _getAlbums(1, 'desc', $slug);
}

function _getAlbums($limit = null, $order = 'desc', $slug = '')
{
    return _doGetData([
        'model' => \ZetthCore\Models\Album::class,
        'slug' => $slug,
        'with' => ['photo'],
        'with_count' => ['photos'],
        'limit' => $limit,
        'order' => $order,
    ]);
}

function _getPhotos($limit = null, $order = 'desc')
{
    return _doGetData([
        'model' => \ZetthCore\Models\AlbumDetail::class,
        'status' => null,
        'with' => ['album'],
        'limit' => $limit,
        'order' => $order,
    ]);
}

function _getVideo($slug = '')
{
    return _getVideos(1, 'desc', $slug);
}

function _getVideos($limit = null, $order = 'desc', $slug = '')
{
    return _doGetData([
        'model' => \ZetthCore\Models\Album::class,
        'slug' => $slug,
        'type' => 'video',
        'limit' => $limit,
        'order' => $order,
    ]);
}
