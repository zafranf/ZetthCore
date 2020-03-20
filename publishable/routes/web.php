<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/* Site Routes */
Route::name('web.')->middleware(['site'])->group(function () {
    Route::post('/login', 'Auth\LoginController@login')->name('login.post');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout.post');
    if (env('APP_DEBUG')) {
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout.get');
        Route::get('/test', 'TestController@index')->name('test');
    }

    /* Action Routes */
    Route::post('/contact', 'Site\ActionController@contact')->name('contact.post');
    Route::post('/comment', 'Site\ActionController@comment')->name('comment.post');
    Route::post('/subscribe', 'Site\ActionController@subscribe')->name('subscribe.post');
    Route::get('/unsubscribe/{email}/{token}', 'Site\ActionController@unsubscribe')->name('unsubscribe');

    /* include additional menu admin */
    $siteRoute = base_path('routes') . '/site.php';
    if (file_exists($siteRoute)) {
        include $siteRoute;
    }

    /* Log all visits */
    Route::middleware(['visitor_log'])->group(function () {
        /* check login status */
        Route::middleware(['guest'])->group(function () {
            Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        });

        /* Front Routes */
        Route::get('/', 'Site\MainController@index')->name('root');
        Route::get('/search', 'Site\MainController@search')->name('search');

        /* Gallery Routes */
        Route::get('/albums', 'Site\GalleryController@album')->name('albums');
        Route::get('/album/{slug}', 'Site\GalleryController@album_detail')->name('album');
        Route::get('/videos', 'Site\GalleryController@video')->name('videos');
        Route::get('/video/{slug}', 'Site\GalleryController@video_detail')->name('video');

        /* Misc Routes */
        Route::get('/comingsoon', 'Site\MiscController@comingsoon')->name('comingsoon');
        Route::get('/maintenance', 'Site\MiscController@maintenance')->name('maintenance');
        Route::get('/thankyou', 'Site\MiscController@thankyou')->name('thankyou');

        /* Term Routes */
        Route::get('/{term_type}/{slug}', 'Site\PostController@term')->name('term')->where('term_type', 'category|tag');

        /* Post Routes */
        Route::get('/author/{name?}', 'Site\PostController@index')->name('posts.author');
        Route::get('/{type}', 'Site\PostController@index')->name('posts')->where('type', 'posts|articles|news');
        Route::get('/{type}/{slug}', 'Site\PostController@detail')->name('post')->where('type', 'post|article|news');

        /* Page Route */
        Route::get('/contact', 'Site\PageController@contact')->name('contact');
        Route::get('/{slug?}', 'Site\PageController@index')->name('page');
    });
});
