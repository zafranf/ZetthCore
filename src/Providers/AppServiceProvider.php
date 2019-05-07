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
        if (env('APP_DOMAIN') === null) {
            throw new \Exception("Please set your APP_DOMAIN in .env file", 1);
        }
        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'zetthcore');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        // $this->loadSeedsFrom(__DIR__ . '/../../database/seeds');
        if ($this->app->runningInConsole()) {
            $this->commands([
                \ZetthCore\Console\Commands\Install::class,
                \ZetthCore\Console\Commands\Reinstall::class,
            ]);
        }

        /* $this->publishes([
        __DIR__ . '/../../database' => database_path(),
        ], 'zetthmigrate'); */
        $this->publishes([
            __DIR__ . '/../../config/laratrust.php' => config_path('laratrust.php'),
            __DIR__ . '/../../config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
        ], 'zetthtrust');

        /* set default varchar */
        Schema::defaultStringLength(191);

        /* check config */
        if (!$this->app->runningInConsole()) {
            if (!Schema::hasTable('applications')) {
                /* sementara, nanti redirect ke halaman install */
                throw new \Exception("You have to install this app first", 1);
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
