<?php
$prefix = '\ZetthCore\Http\Controllers';

Route::get('/', function () {
    return redirect(adminPath() . '/login');
})->name('index');
Route::get('/webmail', function () {
    if (config('app.webmail_url') != '/webmail') {
        return redirect(_url(config('app.webmail_url')));
    }

    abort(404);
})->name('webmail');

Route::middleware(['throttle:' . (config('app.debug') ? 60 : 10) . ',1'])->group(function () use ($prefix) {
    Route::post('/login', $prefix . '\Auth\LoginController@login')->name('login.post');
});

Route::get('/test/connection', function () {
    return response()->json(['status' => true]);
})->name('test.connection');

/* template admin route */
Route::get('/themes/admin/{path}', $prefix . '\AdminController@themes')->where('path', '.*')->name('themes.admin');

Route::middleware(['guest'])->group(function () use ($prefix) {
    Route::get('/login', $prefix . '\Auth\LoginController@showLoginForm')->name('login.form');
    Route::get('/forgot-password', function () {
        $route = route('web.forgot.password');
        if (url()->current() == route('web.forgot.password')) {
            $route = getSiteURL(adminPath() . str_replace(_url('/'), '', $route));
        }

        return redirect($route);
    });
});

Route::middleware('auth')->group(function () use ($prefix) {
    Route::post('/logout', $prefix . '\Auth\LoginController@logout')->name('logout.post');
    if (config('app.debug')) {
        Route::get('/logout', $prefix . '\Auth\LoginController@logout')->name('logout.get');
    }

    /* guide */
    Route::get('/guide', $prefix . '\GuideController@index')->name('guide');

    /* file manager */
    Route::any('/larafile/{path}', $prefix . '\AdminController@getLarafile')->where('path', '.*')->name('larafile');

    /* account */
    Route::get('/account', $prefix . '\AccountController@index')->name('user.account');
    Route::put('/account', $prefix . '\AccountController@update')->name('user.account.update');

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

    /* other action */
    Route::get('/report/comments/{type}/{id}', $prefix . '\AdminController@commentApproval')->name('report.comments.approval')->where('type', 'approve|unapprove');
    // Route::get('/report/comments/unapprove/{id}', $prefix . '\AdminController@commentApproval')->name('report.comments.unapprove');

    Route::middleware('access')->group(function () use ($prefix) {
        /* dashboard */
        Route::get('/dashboard', $prefix . '\DashboardController@index')->name('dashboard.index');

        /* module setting routes */
        Route::prefix('setting')->name('setting.')->group(function () use ($prefix) {
            /* index group */
            Route::get('/', function () {
                return 'setting';
            })->name('index');

            /* api datatable */
            Route::get('/menus/data', $prefix . '\Setting\MenuController@datatable')->name('menus.datatable');
            Route::get('/menu-groups/data', $prefix . '\Setting\MenuGroupController@datatable')->name('menu-groups.datatable');
            Route::get('/roles/data', $prefix . '\Setting\RoleController@datatable')->name('roles.datatable');

            /* main menu */
            Route::resources([
                '/site' => $prefix . '\Setting\SiteController',
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
        Route::prefix('data')->name('data.')->group(function () use ($prefix) {
            /* index group */
            Route::get('/', function () {
                return 'data';
            })->name('index');

            /* api datatable */
            Route::get('/users/data', $prefix . '\Data\UserController@datatable')->name('users.datatable');
            Route::get('/categories/data', $prefix . '\Data\CategoryController@datatable')->name('categories.datatable');
            Route::get('/tags/data', $prefix . '\Data\TagController@datatable')->name('tags.datatable');

            /* main menu */
            Route::resources([
                '/users' => $prefix . '\Data\UserController',
                '/categories' => $prefix . '\Data\CategoryController',
                '/tags' => $prefix . '\Data\TagController',
            ]);
        });

        /* module content routes */
        Route::prefix('content')->name('content.')->group(function () use ($prefix) {
            /* index group */
            Route::get('/', function () {
                return 'content';
            })->name('index');

            /* api datatable */
            Route::get('/pages/data', $prefix . '\Content\PageController@datatable')->name('pages.datatable');
            Route::get('/posts/data', $prefix . '\Content\PostController@datatable')->name('posts.datatable');
            Route::get('/banners/data', $prefix . '\Content\BannerController@datatable')->name('banners.datatable');

            /* main menu */
            Route::resources([
                '/pages' => $prefix . '\Content\PageController',
                '/posts' => $prefix . '\Content\PostController',
                '/banners' => $prefix . '\Content\BannerController',
            ]);

            /* gallery */
            Route::prefix('gallery')->name('gallery.')->group(function () use ($prefix) {
                /* index group */
                Route::get('/', function () {
                    return 'content.gallery';
                })->name('index');

                /* api datatable */
                Route::get('/photos/data', $prefix . '\Content\Gallery\PhotoController@datatable')->name('photos.datatable');
                Route::get('/videos/data', $prefix . '\Content\Gallery\VideoController@datatable')->name('videos.datatable');

                /* main menu */
                Route::resources([
                    '/photos' => $prefix . '\Content\Gallery\PhotoController',
                    '/videos' => $prefix . '\Content\Gallery\VideoController',
                ], [
                    'parameters' => [
                        'photos' => 'album',
                    ],
                ]);
            });
        });

        /* module report routes */
        Route::prefix('report')->name('report.')->group(function () use ($prefix) {
            /* index group */
            Route::get('/', function () {
                return 'report';
            })->name('index');

            /* api datatable */
            Route::get('/inbox/data', $prefix . '\Report\InboxController@datatable')->name('inbox.datatable');
            Route::get('/comments/data', $prefix . '\Report\CommentController@datatable')->name('comments.datatable');
            Route::get('/incoming-terms/data', $prefix . '\Report\IntermController@datatable')->name('interms.datatable');
            Route::get('/subscribers/data', $prefix . '\Report\SubscriberController@datatable')->name('subscribers.datatable');

            /* main menu */
            Route::resources([
                '/inbox' => $prefix . '\Report\InboxController',
                '/comments' => $prefix . '\Report\CommentController',
                '/incoming-terms' => $prefix . '\Report\IntermController',
                '/subscribers' => $prefix . '\Report\SubscriberController',
            ]);
        });

        /* module log routes */
        Route::prefix('log')->name('log.')->group(function () use ($prefix) {
            /* index group */
            Route::get('/', function () {
                return 'log';
            })->name('index');

            /* api datatable */
            Route::get('/activities/data', $prefix . '\Log\ActivityController@datatable')->name('activities.datatable');
            Route::get('/errors/data', $prefix . '\Log\ErrorController@datatable')->name('errors.datatable');
            Route::get('/visitors/data', $prefix . '\Log\VisitorController@datatable')->name('visitors.datatable');

            /* main menu */
            Route::resources([
                '/activities' => $prefix . '\Log\ActivityController',
                '/errors' => $prefix . '\Log\ErrorController',
                '/visitors' => $prefix . '\Log\VisitorController',
            ]);
        });
    });
});

/* include additional menu admin */
$siteAdmin = base_path('routes') . '/admin.php';
if (file_exists($siteAdmin)) {
    include $siteAdmin;
}
