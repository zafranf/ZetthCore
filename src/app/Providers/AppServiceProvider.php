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
        $router = $this->app['router'];
        $router->aliasMiddleware('access', \ZetthCore\Http\Middleware\AccessMiddleware::class);
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                \ZetthCore\Console\Commands\Install::class,
                \ZetthCore\Console\Commands\Reinstall::class,
            ]);
        }

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
                'is_mobile' => $agent->isMobile(),
                'is_tablet' => $agent->isTablet(),
                'is_desktop' => $agent->isDesktop(),
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