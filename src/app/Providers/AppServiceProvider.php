<?php

namespace ZetthCore\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        /* set default varchar */
        Schema::defaultStringLength(191);

        /* check config */
        $isCLI = strpos(php_sapi_name(), 'cli') !== false;
        if (!$isCLI) {
            if (!Schema::hasTable('applications')) {
                /* sementara, nanti redirect ke halaman install */
                dd('You need to install this app first');
                // redirect(url('/install'))->send();
            }

            /* check admin page */
            $adminPath = '/admin';
            $isAdminSubdomain = false;
            $isAdminPanel = false;

            /* check admin on uri */
            $uri = _server('REQUEST_URI');
            if (strpos($uri, 'admin') !== false) {
                $isAdminPanel = true;
            }

            /* check admin on host */
            $host = parse_url(url('/'))['host'];
            if (strpos($host, 'admin') !== false) {
                $adminPath = '';
                $isAdminSubdomain = true;
                $isAdminPanel = true;
            }

            /* share admin panel variable */
            View::share([
                'adminPath' => $adminPath,
                'isAdminSubdomain' => $isAdminSubdomain,
                'isAdminPanel' => $isAdminPanel,
            ]);

            /* send application data to all views */
            $apps = \ZetthCore\Models\Application::where('domain', $host)->first();
            if (!$apps) {
                throw new \Exception("Application config not found", 1);
            }
            View::share('apps', $apps);

            /* send device type to all views */
            $agent = new \Jenssegers\Agent\Agent();
            View::share([
                'isMobile' => $agent->isMobile(),
                'isTablet' => $agent->isTablet(),
                'isDesktop' => $agent->isDesktop(),
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
