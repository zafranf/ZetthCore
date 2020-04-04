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
    Route::post('/login/email', 'Auth\LoginController@loginByEmail')->name('login.email');
    Route::get('/login/email/callback', 'Auth\LoginController@loginByEmailCallback')->name('login.email.callback');

    /* Auth */
    Route::middleware(['throttle:' . (env('APP_DEBUG') ? 60 : 10) . ',1'])->group(function () {
        Route::post('/register', 'Auth\RegisterController@register')->name('register.post');
        Route::post('/login', 'Auth\LoginController@login')->name('login.post');
        Route::post('/verification', 'Auth\VerificationController@verify')->name('verify.post');
        Route::post('/verification/resend', 'Auth\VerificationController@resend')->name('verify.resend.post');
        Route::post('/forgot-password', 'Auth\ForgotPasswordController@send')->name('forgot.post');
        Route::post('/reset-password', 'Auth\ResetPasswordController@store')->name('reset.post');
        Route::post('/logout', 'Auth\LoginController@logout')->name('logout.post');
    });
    if (env('APP_DEBUG')) {
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('/test', 'TestController@index')->name('test');
    }

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

    /* include additional menu site */
    $siteRoute = base_path('routes') . '/site.php';
    if (file_exists($siteRoute)) {
        include $siteRoute;
    }

    /* Log all visits */
    Route::middleware(['visitor_log'])->group(function () {
        /* check login status */
        Route::middleware(['guest'])->group(function () {
            Route::get('/register', 'Auth\LoginController@showRegistrationForm')->name('register');
            Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
            Route::get('/verification/{type}', 'Auth\VerificationController@index')->name('verify');
            Route::get('/forgot-password', 'Auth\ForgotPasswordController@index')->name('forgot.password');
            Route::get('/reset-password', 'Auth\ResetPasswordController@index')->name('reset.password');
        });

        /* must logged in */
        Route::middleware(['auth'])->group(function () {
            Route::post('/profile', 'Site\UserController@update')->name('profile.post');

            Route::get('/profile', 'Site\UserController@index')->name('profile');
            Route::get('/profile/edit', 'Site\UserController@edit')->name('profile.edit');
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

        /* Posts by Routes */
        Route::get('/{by_type}/{slug}', 'Site\PostController@by')->name('posts.by')->where('by_type', 'category|tag|author');

        /* Posts Routes */
        Route::get('/{type}', 'Site\PostController@index')->name('posts')->where('type', 'posts|articles|news');
        Route::get('/{type}/{slug}', 'Site\PostController@detail')->name('post')->where('type', 'post|article|news');

        /* Page Route */
        Route::get('/contact', 'Site\PageController@contact')->name('contact');
        Route::get('/{slug}', 'Site\PageController@index')->name('page');
    });
});
