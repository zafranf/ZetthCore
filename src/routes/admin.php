<?php
$prefix = '\ZetthCore\Http\Controllers';

Route::get('/', function () {
    return redirect(adminPath() . '/login');
})->name('index');
Route::get('/login', $prefix . '\Auth\LoginController@showLoginForm')->name('login.form');
Route::post('/login', $prefix . '\Auth\LoginController@login')->name('login.post');
Route::get('/test/connection', function () {
    return response()->json(['status' => true]);
})->name('test.connection');

Route::middleware('auth')->group(function () use ($prefix) {
    Route::post('/logout', $prefix . '\Auth\LoginController@logout')->name('logout.post');
    if (env('APP_DEBUG')) {
        Route::get('/logout', $prefix . '\Auth\LoginController@logout')->name('logout.get');
    }

    /* file manager */
    Route::any('/larafile/{path}', function ($path) {
        $path = base_path('vendor/zafranf/zetthcore/src/resources/themes/AdminSC/plugins/filemanager/' . $path);
        if (ends_with($path, '.php')) {
            require $path;
        } else {
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
    })->where('path', '.*')->name('larafile');
    Route::any('/larafile-standalone/{path}', function ($path) {
        $path = base_path('vendor/zafranf/zetthcore/src/resources/themes/AdminSC/plugins/filemanager-standalone/' . $path);
        if (ends_with($path, '.php')) {
            require $path;
        } else {
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
    })->where('path', '.*')->name('larafile-standalone');

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
