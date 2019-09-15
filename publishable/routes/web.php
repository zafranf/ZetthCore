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
Route::name('web.')->middleware('visitor_log')->group(function () {
    /* Front Routes */
    Route::get('/', 'Site\MainController@index')->name('root');
    Route::get('/contact', 'Site\MainController@contact')->name('contact');
    Route::get('/search', 'Site\MainController@search')->name('search');

    /* Action Routes */
    Route::post('/contact', 'Site\ActionController@contact')->name('contact.post');
    Route::post('/comment', 'Site\ActionController@comment')->name('comment.post');
    Route::post('/subscribe', 'Site\ActionController@subscribe')->name('subscribe.post');
    Route::get('/unsubscribe/{email}/{token}', 'Site\ActionController@unsubscribe')->name('unsubscribe');

    /* Gallery Routes */
    Route::get('/albums', 'Site\GalleryController@album')->name('albums');
    Route::get('/album/{slug}', 'Site\GalleryController@album_detail')->name('album');
    Route::get('/photos', 'Site\GalleryController@photo')->name('photos');
    Route::get('/photo/{slug}', 'Site\GalleryController@photo_detail')->name('photo');
    Route::get('/videos', 'Site\GalleryController@video')->name('videos');
    Route::get('/video/{slug}', 'Site\GalleryController@video_detail')->name('video');

    /* Misc Routes */
    Route::get('/comingsoon', 'Site\MiscController@comingsoon')->name('comingsoon');
    Route::get('/maintenance', 'Site\MiscController@maintenance')->name('maintenance');
    Route::get('/thankyou', 'Site\MiscController@thankyou')->name('thankyou');

    /* Term Routes */
    Route::get('/{type:category|tag}/{slug}', 'Site\TermController@index')->name('term');

    /* Post Routes */
    Route::get('/{type:posts|articles|news}', 'Site\PostController@index')->name('posts');
    Route::get('/{type:posts|articles|news}/{slug}', 'Site\PostController@detail')->name('post');

    /* Page Route */
    Route::get('/{slug?}', 'Site\PageController@index')->name('page');
});
