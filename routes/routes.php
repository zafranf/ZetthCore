<?php
Route::get('/themes/admin/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*')->name('themes.admin');

/* admin routes */
Route::middleware('web')->name('admin.')->group(function () {
    if (env('ADMIN_ROUTE', 'path') == 'path') {
        Route::prefix('admin')->group(function () {
            include __DIR__ . "/admin.php";
        });
    } else if (env('ADMIN_ROUTE') == 'subdomain') {
        Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
            include __DIR__ . "/admin.php";
        });
    }
});
