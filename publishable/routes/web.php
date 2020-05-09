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
    /* Oauth */
    Route::get('/login/{driver}', 'Auth\LoginController@redirectToProvider')->name('login.driver');
    Route::get('/login/{driver}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.driver.callback');

    /* Auth */
    Route::middleware(['throttle:' . (env('APP_DEBUG') ? 60 : 10) . ',1'])->group(function () {
        Route::post('/register', 'Auth\RegisterController@register')->name('register.post');
        Route::post('/login', 'Auth\LoginController@login')->name('login.post');
        Route::post('/verification', 'Auth\VerificationController@verify')->name('verify.post');
        Route::post('/verification/resend', 'Auth\VerificationController@resend')->name('verify.resend.post');
        Route::post('/forgot-password', 'Auth\ForgotPasswordController@send')->name('forgot.post');
        Route::post('/reset-password', 'Auth\ResetPasswordController@store')->name('reset.post');
    });
    if (env('APP_DEBUG')) {
        Route::get('/test', 'TestController@index')->name('test');
    }

    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', 'Auth\LoginController@logout')->name('logout.post');
        if (env('APP_DEBUG')) {
            Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
        }

        /* POST */
        Route::put('/profile', 'Site\UserController@update')->name('profile.put');
    });

    /* Action Routes */
    Route::prefix('/action')->name('action.')->group(function () {
        Route::post('/like', 'Site\ActionController@like')->name('like');
        Route::post('/dislike', 'Site\ActionController@dislike')->name('dislike');
        Route::post('/contact', 'Site\ActionController@contact')->name('contact');
        Route::post('/comment', 'Site\ActionController@comment')->name('comment');
        Route::post('/subscribe', 'Site\ActionController@subscribe')->name('subscribe');
        Route::get('/unsubscribe/{email}/{token}', 'Site\ActionController@unsubscribe')->name('unsubscribe');
        Route::get('/share/{slug}/{socmed}', 'Site\ActionController@share')->name('share');
    });

    /* Log all visits */
    Route::middleware(['visitor_log'])->group(function () {
        /* check login status */
        Route::middleware(['guest'])->group(function () {
            Route::get('/' . config('path.register'), 'Auth\LoginController@showRegistrationForm')->name('register');
            Route::get('/' . config('path.verification') . '/{type}', 'Auth\VerificationController@index')->name('verify');
            Route::get('/' . config('path.login'), 'Auth\LoginController@showLoginForm')->name('login');
            Route::get('/' . config('path.forgotpass'), 'Auth\ForgotPasswordController@index')->name('forgot.password');
            Route::get('/' . config('path.resetpass'), 'Auth\ResetPasswordController@index')->name('reset.password');
        });

        /* must logged in */
        Route::middleware(['auth'])->group(function () {
            /* GET */
            Route::get('/' . config('path.profile'), 'Site\UserController@index')->name('profile');
            Route::get('/' . config('path.profile') . '/edit', 'Site\UserController@edit')->name('profile.edit');
        });

        /* Front Routes */
        Route::get('/', 'Site\MainController@index')->name('root');
        Route::get('/' . config('path.search'), 'Site\MainController@search')->name('search');

        /* Gallery Routes */
        Route::get('/' . config('path.albums'), 'Site\GalleryController@album')->name('albums');
        Route::get('/' . config('path.album') . '/{slug}', 'Site\GalleryController@album_detail')->name('album');
        Route::get('/' . config('path.videos'), 'Site\GalleryController@video')->name('videos');
        Route::get('/' . config('path.video') . '/{slug}', 'Site\GalleryController@video_detail')->name('video');

        /* Misc Routes */
        Route::get('/' . config('path.comingsoon'), 'Site\MiscController@comingsoon')->name('comingsoon');
        Route::get('/' . config('path.maintenance'), 'Site\MiscController@maintenance')->name('maintenance');

        /* Posts by Routes */
        Route::get('/{by_type}/{slug}', 'Site\PostController@by')->name('posts.by')->where('by_type', config('path.category') . '|' . config('path.tag') . '|' . config('path.author'));

        /* Posts Routes */
        Route::get('/{type}', 'Site\PostController@index')->name('posts')->where('type', config('path.posts'));
        Route::get('/{type}/{slug}', 'Site\PostController@detail')->name('post')->where('type', config('path.post'));

        /* Page Route */
        Route::get('/' . config('path.contact'), 'Site\PageController@contact')->name('contact');
    });

    /* include additional menu site */
    $siteRoute = base_path('routes') . '/site.php';
    if (file_exists($siteRoute)) {
        include $siteRoute;
    }

    /* Log all visits */
    Route::middleware(['visitor_log'])->group(function () {
        /* Page Route */
        Route::get('/{slug}', 'Site\PageController@index')->where('slug', '.*')->name('page');
    });
});
