<?php
/* admin routes */
Route::middleware('web')->name('admin.')->group(function () {
    if (adminRoute() == 'path') {
        Route::prefix(adminPath())->group(function () {
            include __DIR__ . "/admin.php";
        });
    } else if (adminRoute() == 'subdomain') {
        Route::domain(adminSubdomain() . '.' . config('app.domain'))->group(function () {
            include __DIR__ . "/admin.php";
        });
    }
});

/* template site route */
Route::get('/themes/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*')->name('themes.site');
