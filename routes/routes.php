<?php
Route::get('/themes/admin/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*')->name('themes.admin');

/* admin routes */
Route::middleware('web')->name('admin.')->group(function () {
    $admin_route = env('ADMIN_ROUTE', 'path');
    if ($admin_route == 'path') {
        Route::prefix('admin')->group(function () {
            include __DIR__ . "/admin.php";
        });
    } else if ($admin_route == 'subdomain') {
        Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
            include __DIR__ . "/admin.php";
        });
    }
});
