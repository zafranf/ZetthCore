<?php

namespace ZetthCore\Providers;

use Illuminate\Support\Facades\Schema;
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
        /* check config */
        if (!$this->app->runningInConsole()) {
            if (!Schema::hasTable('applications')) {
                /* sementara, nanti redirect ke halaman install */
                throw new \Exception("You have to install this app first", 1);
                // redirect(url('/install'))->send();
            }

            /* check admin page */
            $adminPath = '/' . env('ADMIN_PATH', 'admin');
            $isAdminSubdomain = false;
            $isAdminPanel = false;

            /* check admin on uri */
            $uri = _server('REQUEST_URI');
            if (strpos($uri, env('ADMIN_PATH', 'admin')) !== false) {
                $isAdminPanel = true;
            }

            /* check admin on host */
            $host = parse_url(url('/'))['host'];
            if (strpos($host, env('ADMIN_SUBDOMAIN', 'admin')) !== false) {
                $adminPath = '';
                $isAdminSubdomain = true;
                $isAdminPanel = true;
            }

            /* View::share([
            'adminPath' => $adminPath,
            'isAdminSubdomain' => $isAdminSubdomain,
            'isAdminPanel' => $isAdminPanel,
            ]); */
            /* share admin panel to global */
            $this->app->singleton('admin_path', function () use ($adminPath) {
                return $adminPath;
            });
            $this->app->singleton('is_admin_subdomain', function () use ($isAdminSubdomain) {
                return $isAdminSubdomain;
            });
            $this->app->singleton('is_admin_panel', function () use ($isAdminPanel) {
                return $isAdminPanel;
            });

            /* get application setting */
            $apps = \ZetthCore\Models\Application::where('domain', $host)->with('socmed_data', 'socmed_data.socmed')->first();
            if (!$apps) {
                throw new \Exception("Application config not found", 1);
            }

            /* set application setting to global */
            $this->app->singleton('setting', function () use ($apps) {
                return $apps;
            });

            /* set device type to global */
            $agent = new \Jenssegers\Agent\Agent();
            $this->app->singleton('is_mobile', function () use ($agent) {
                return $agent->isMobile();
            });
            $this->app->singleton('is_tablet', function () use ($agent) {
                return $agent->isTablet();
            });
            $this->app->singleton('is_desktop', function () use ($agent) {
                return $agent->isDesktop();
            });
        } else if ($this->app->runningInConsole()) {
            $this->commands([
                \ZetthCore\Console\Commands\Install::class,
                \ZetthCore\Console\Commands\Reinstall::class,
            ]);
        }

        /* set middleware */
        $router = $this->app['router'];
        $router->aliasMiddleware('access', \ZetthCore\Http\Middleware\AccessMiddleware::class);
        $router->aliasMiddleware('visitor_log', \ZetthCore\Http\Middleware\VisitorLogMiddleware::class);
        $router->middleware([
            \RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class,
            \RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class,
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'zetthcore');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        // $this->loadSeedsFrom(__DIR__ . '/../../database/seeds');

        /* $this->publishes([
        __DIR__ . '/../../database' => database_path(),
        ], 'zetthmigrate'); */
        $publishable_path = '/../../../publishable';
        $this->publishes([
            __DIR__ . $publishable_path . '/config/auth.php' => config_path('auth.php'),
            __DIR__ . $publishable_path . '/config/database.php' => config_path('database.php'),
            __DIR__ . $publishable_path . '/config/image.php' => config_path('image.php'),
            __DIR__ . $publishable_path . '/config/laratrust.php' => config_path('laratrust.php'),
            __DIR__ . $publishable_path . '/config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
        ], 'zetthconfig');
        // $this->publishes([
        //     __DIR__ . $publishable_path.'/config/auth.php' => config_path('auth.php'),
        // ], 'zetthauth');
        $this->publishes([
            __DIR__ . $publishable_path . '/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
        ], 'zetthhandler');
        $this->publishes([
            __DIR__ . $publishable_path . '/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            __DIR__ . $publishable_path . '/Middleware/RedirectIfAuthenticated.php' => app_path('Http/Middleware/RedirectIfAuthenticated.php'),
        ], 'zetthmiddleware');
        $this->publishes([
            __DIR__ . $publishable_path . '/routes/web.php' => base_path('routes/web.php'),
        ], 'zetthroutes');

        /* set default varchar */
        Schema::defaultStringLength(191);
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
