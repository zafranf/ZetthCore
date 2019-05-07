<?php
$prefix = '\ZetthCore\Http\Controllers';
Route::get('/', function () {
    if (env('ADMIN_ROUTE', 'path') == 'path') {
        return redirect('/admin/login');
    } else {
        return redirect('/login');
    }
});
Route::get('/test-connection', function () {
    return response()->json(['status' => true]);
});
Route::get('/login', $prefix . '\Auth\LoginController@showLoginForm');
Route::post('/login', $prefix . '\Auth\LoginController@login');
Route::post('/logout', $prefix . '\Auth\LoginController@logout');
if (env('APP_DEBUG')) {
    Route::get('/logout', $prefix . '\Auth\LoginController@logout');
}

Route::middleware('auth')->group(function () use ($prefix) {
    /* api datatable */
    Route::get('/setting/menus/data', $prefix . '\Setting\MenuController@datatable')->name('menus.data');
    Route::get('/setting/menu-groups/data', $prefix . '\Setting\MenuGroupController@datatable')->name('menu-groups.data');
    Route::get('/setting/roles/data', $prefix . '\Setting\RoleController@datatable')->name('roles.data');
    Route::get('/data/users/data', $prefix . '\Data\UserController@datatable')->name('users.data');
    Route::get('/data/categories/data', $prefix . '\Data\CategoryController@datatable')->name('categories.data');
    Route::get('/data/tags/data', $prefix . '\Data\TagController@datatable')->name('tags.data');
    Route::get('/content/pages/data', $prefix . '\Content\PageController@datatable')->name('pages.data');
    Route::get('/content/posts/data', $prefix . '\Content\PostController@datatable')->name('posts.data');
    // Route::get('/content/banners/data', $prefix.'\Content\BannerController@datatable')->name('banners.data');
    Route::get('/report/inbox/data', $prefix . '\Report\InboxController@datatable')->name('inbox.data');
    Route::get('/report/comments/data', $prefix . '\Report\CommentController@datatable')->name('comments.data');
    // Route::get('/report/incoming-terms/data', $prefix . '\Report\IntermController@datatable')->name('interms.data');
    // Route::get('/report/subscribers/data', $prefix . '\Report\SubscriberController@datatable')->name('subscribers.data');
    Route::get('/log/activities/data', $prefix . '\Log\ActivityController@datatable')->name('activities.data');
    Route::get('/log/errors/data', $prefix . '\Log\ErrorController@datatable')->name('errors.data');
    Route::get('/log/visitors/data', $prefix . '\Log\VisitorController@datatable')->name('visitors.data');

    /* api ajax */
    Route::get('/ajax/pageview', $prefix . '\AjaxController@pageview')->name('ajax.pageview');
    Route::get('/ajax/popularpost', $prefix . '\AjaxController@popularpost')->name('ajax.popularpost');
    Route::get('/ajax/recentcomment', $prefix . '\AjaxController@recentcomment')->name('ajax.recentcomment');
    Route::get('/ajax/term/{term}', $prefix . '\AjaxController@term')->name('ajax.term');

    /* sort menu */
    // Route::get('/setting/menus/sort', $prefix.'\Setting\MenuController@sort')->name('menus.sort');
    // Route::put('/setting/menus/sort', $prefix.'\Setting\MenuController@sortSave')->name('menus.sortSave');

    /* sort banner */
    /* Route::get('/content/banners/sort', $prefix.'\Content\BannerController@sort')->name('banners.sort')->name('banners.sort');
    Route::put('/content/banners/sort', $prefix.'\Content\BannerController@sortSave')->name('banners.sortSave'); */

    Route::middleware('access')->group(function () use ($prefix) {
        /* dashboard */
        Route::get('/dashboard', $prefix . '\DashboardController@index')->name('dashboard.index');

        /* module setting routes */
        Route::prefix('setting')->group(function () use ($prefix) {
            Route::resources([
                '/application' => $prefix . '\Setting\ApplicationController',
                '/menus' => $prefix . '\Setting\MenuController',
                '/menu-groups' => $prefix . '\Setting\MenuGroupController',
                '/roles' => $prefix . '\Setting\RoleController',
            ], [
                'parameters' => [
                    'menu-groups' => 'menugroup',
                ],
            ]);
        });

        /* module data routes */
        Route::prefix('data')->group(function () use ($prefix) {
            Route::resources([
                '/users' => $prefix . '\Data\UserController',
                '/categories' => $prefix . '\Data\CategoryController',
                '/tags' => $prefix . '\Data\TagController',
            ]);
        });

        /* module content routes */
        Route::prefix('content')->group(function () use ($prefix) {
            Route::resources([
                '/pages' => $prefix . '\Content\PageController',
                '/posts' => $prefix . '\Content\PostController',
                // '/banners' => $prefix.'\Content\BannerController',
                // '/gallery/photos' => $prefix.'\Content\Gallery\PhotoController',
                // '/gallery/videos' => $prefix.'\Content\Gallery\VideoController',
                // '/products' => $prefix.'\Content\Product\ProductController',
            ]);
            // Route::resource('/products/categories', $prefix.'\Content\Product\CategoryController')->names('products.categories');
            // Route::resource('/products/tags', $prefix.'\Content\Product\TagController')->names('products.tags');
        });

        /* module report routes */
        Route::prefix('report')->group(function () use ($prefix) {
            Route::resources([
                '/inbox' => $prefix . '\Report\InboxController',
                '/comments' => $prefix . '\Report\CommentController',
                '/incoming-terms' => $prefix . '\Report\IntermController',
                '/subscribers' => $prefix . '\Report\SubscriberController',
            ]);
        });

        /* module log routes */
        Route::prefix('log')->group(function () use ($prefix) {
            Route::resources([
                '/activities' => $prefix . '\Log\ActivityController',
                '/errors' => $prefix . '\Log\ErrorController',
                '/visitors' => $prefix . '\Log\VisitorController',
            ]);
        });

    });
});
