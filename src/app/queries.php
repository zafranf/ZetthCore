<?php
function _doGetData($cache_name, $data, $limit = null, $pagename = 'page')
{
    /* set page limit */
    $page = \Request::input($pagename) ? \Request::input($pagename) : 1;
    $limit = $limit ?? app('site')->perpage;

    /* set cache */
    $cache_name .= $limit . $page . app('site')->id;

    $data = \Cache::remember($cache_name, getCacheTime(), function () use ($data, $limit, $pagename) {
        /* cek limit */
        if ($limit > 1) {
            $data = $data->paginate($limit, '*', $pagename);
        } else if ($limit == 1) {
            $data = $data->first();
        } else {
            $data = $data->get();
        }

        return $data ?? '-';
    });

    return $data != '-' ? $data : null;
}

function _getBanners($limit = null, $pagename = 'page')
{
    /* inisiasi query */
    $banners = \ZetthCore\Models\Banner::active()->orderBy('order', 'asc');

    return _doGetData('getBanners', $banners, $limit, $pagename);
}

function _getPost($slug, $type = 'complete', $pagename = 'page')
{
    return _getPosts($type, 1, 'desc', $slug, $pagename);
}

function _getPosts($type = 'simple', $limit = null, $order = 'desc', $slug = '', $pagename = 'page')
{
    /* check it's complete request */
    $complete = $type == 'complete';

    /* cache name */
    $cache_name = 'getPosts' . $type . $order . $slug;

    /* inisiasi query */
    $posts = \App\Models\Post::active()->posts();
    if (!empty($slug)) {
        if (in_array($type, ['category', 'tag'])) {
            if ($type == 'category') {
                $posts->withCategory($slug);
            } else if ($type == 'tag') {
                $posts->withTag($slug);
            }
        } else if ($type == 'author') {
            $posts->withAuthor($slug);
        } else if ($type == 'search') {
            $posts->where('title', 'like', '%' . $slug . '%');
        } else {
            $posts->where('slug', $slug);
        }
    }

    /* before or equal publish date */
    $posts->where('published_at', '<=', now());

    /* check complete params */
    if ($complete) {
        $posts->with('comments_sub', 'categories', 'tags', 'author', 'editor', 'likes_user');
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

    return _doGetData($cache_name, $posts, $limit, $pagename);
}

function _getPostsSimple($limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('simple', $limit, $order, '', $pagename);
}

function _getPostsComplete($limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('complete', $limit, $order, '', $pagename);
}

function _getCategoryPosts($slug, $limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('category', $limit, $order, $slug, $pagename);
}

function _getTagPosts($slug, $limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('tag', $limit, $order, $slug, $pagename);
}

function _getAuthorPosts($slug, $limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('author', $limit, $order, $slug, $pagename);
}

function _getSearchPosts($slug, $limit = null, $order = 'desc', $pagename = 'page')
{
    return _getPosts('search', $limit, $order, $slug, $pagename);
}

function _getTerms($type = 'category', $limit = null, $order = 'desc', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getTerms' . $type . $order;

    /* inisiasi query */
    $terms = \ZetthCore\Models\Term::active()->where('type', $type)->groupBy('slug');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $terms->inRandomOrder();
    } else {
        $terms->orderBy('name', $order);
    }

    return _doGetData($cache_name, $terms, $limit, $pagename);
}

function _getCategory($pagename = 'page')
{
    return _getCategories(1, 'desc', $pagename);
}

function _getCategories($limit = null, $order = 'desc', $pagename = 'page')
{
    return _getTerms('category', $limit, $order, $pagename);
}

function _getTag($pagename = 'page')
{
    return _getTags(1, 'desc', $pagename);
}

function _getTags($limit = null, $order = 'desc', $pagename = 'page')
{
    return _getTerms('tag', $limit, $order, $pagename);
}

function _getPage($slug, $pagename = 'page')
{
    return _getPages(1, 'desc', $slug, $pagename);
}

function _getPages($limit = null, $order = 'desc', $slug = '', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getPages' . $order . $slug;

    /* inisiasi query */
    $pages = \App\Models\Post::active()->pages();
    if (!empty($slug)) {
        $pages->where(function ($q) use ($slug) {
            $q->where('slug', $slug);
            $q->orWhere('title', 'like', '%' . $slug . '%');
        });
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $pages->inRandomOrder();
    } else {
        $pages->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $pages, $limit, $pagename);
}

function _getAlbum($slug, $pagename = 'page')
{
    return _getAlbums(1, 'desc', $slug, $pagename);
}

function _getAlbums($limit = null, $order = 'desc', $slug = '', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getAlbums' . $order . $slug;

    /* inisiasi query */
    $albums = \ZetthCore\Models\Album::active();
    if (!empty($slug)) {
        $albums->where('slug', $slug)->orWhere('name', 'like', '%' . $slug . '%');
    }

    /* relation */
    if ($limit == 1) {
        $albums->with('photos');
    } else {
        $albums->with('photo');
        $albums->withCount('photos');
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $albums->inRandomOrder();
    } else {
        $albums->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $albums, $limit, $pagename);
}

function _getPhotos($limit = null, $order = 'desc', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getPhotos' . $order;

    /* inisiasi query */
    $photos = \App\Models\File::with('albums');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $photos->inRandomOrder();
    } else {
        $photos->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $photos, $limit, $pagename);
}

function _getVideo($slug = '', $pagename = 'page')
{
    return _getVideo(1, 'desc', $slug, $pagename);
}

function _getVideos($limit = null, $order = 'desc', $slug = '', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getVideos' . $order . $slug;

    /* inisiasi query */
    $videos = \App\Models\Post::active()->videos();
    if (!empty($slug)) {
        $videos->where('slug', $slug)->orWhere('name', 'like', '%' . $slug . '%');
    }

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $videos->inRandomOrder();
    } else {
        $videos->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $videos, $limit, $pagename);
}

function _getPopularPosts($limit = null, $start_date = null, $end_date = null, $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getPopularPosts' . $start_date . $end_date;

    /* default start and end date */
    $start_date = $start_date ?? date("Y-m-d", strtotime('-7 days'));
    $end_date = $end_date ?? date("Y-m-d");

    /* set start and end as carbon */
    $start = carbon_query($start_date . ' 00:00:00');
    $end = carbon_query($end_date . ' 23:59:59');

    /* inisiasi query */
    $posts = \ZetthCore\Models\VisitorLog::select(DB::raw('sum(count) as count'), \DB::raw('SUBSTRING_INDEX(page, "/", -1) as slug'))
        ->where('page', 'regexp', '^/(post|article|news)/')
        ->where('count', '>=', 100)
        ->whereBetween('created_at', [$start, $end])
        ->orderBy('count', 'desc')
        ->with('post.categories')
        ->groupBy(\DB::raw('SUBSTRING_INDEX(page, "/", -1)'));

    return _doGetData($cache_name, $posts, $limit, $pagename);
}

function _getComments($limit = null, $order = 'desc', $pagename = 'page')
{
    /* cache name */
    $cache_name = 'getComments' . $order;

    /* inisiasi query */
    $comments = \App\Models\Comment::active()->with('post');

    /* check order */
    if (in_array($order, ['rand', 'random'])) {
        $comments->inRandomOrder();
    } else {
        $comments->orderBy('created_at', $order);
    }

    return _doGetData($cache_name, $comments, $limit, $pagename);
}
