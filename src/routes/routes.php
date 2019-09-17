<?php
/* template site route */
Route::get('/themes/{path}', '\App\Http\Controllers\SiteController@themes')->where('path', '.*')->name('themes.site');

/* template admin route */
Route::get('/themes/admin/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*')->name('themes.admin');

/* admin routes */
Route::middleware('web')->name('admin.')->group(function () {
    $admin_route = env('ADMIN_ROUTE', 'path');
    if ($admin_route == 'path') {
        Route::prefix(env('ADMIN_PATH', 'admin'))->group(function () {
            include __DIR__ . "/admin.php";
        });
    } else if ($admin_route == 'subdomain') {
        Route::domain(env('ADMIN_SUBDOMAIN', 'admin') . '.' . env('APP_DOMAIN'))->group(function () {
            include __DIR__ . "/admin.php";
        });
    }
});
