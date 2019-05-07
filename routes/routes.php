
<?php
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
