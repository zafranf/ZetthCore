
<?php
Route::get('/themes/{path}', '\ZetthCore\Http\Controllers\AdminController@themes')->where('path', '.*');

Route::get('assets', ['uses' => '\ZetthCore\Http\Controllers\AdminController@assets', 'as' => 'assets']);
/* admin routes */
if (env('ADMIN_ROUTE', 'path') == 'path') {
    Route::prefix('admin')->group(function () {
        include __DIR__ . "/admin.php";
    });
} else if (env('ADMIN_ROUTE') == 'subdomain') {
    Route::domain('admin.' . env('APP_DOMAIN'))->group(function () {
        include __DIR__ . "/admin.php";
    });
}
