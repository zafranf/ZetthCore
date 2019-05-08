<?php
Route::get('/themes/admin/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*');
/* admin routes */
if (env('ADMIN_ROUTE', 'path') == 'path') {
    Route::middleware('web')->prefix('admin')->group(function () {
        include __DIR__ . "/admin.php";
    });
} else if (env('ADMIN_ROUTE') == 'subdomain') {
    Route::middleware('web')->domain('admin.' . env('APP_DOMAIN'))->group(function () {
        include __DIR__ . "/admin.php";
    });
}
