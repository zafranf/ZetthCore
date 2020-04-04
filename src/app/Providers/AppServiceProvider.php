<?php

namespace ZetthCore\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use \ZetthCore\Traits\MainTrait;

    private $package_path;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->package_path = base_path('vendor/zafranf/zetthcore');

        /* check config */
        if (!$this->app->runningInConsole()) {
            if (!$this->checkDBConnection() || ($this->checkDBConnection() && !\Schema::hasTable('sites'))) {
                /* sementara, nanti redirect ke halaman install */
                throw new \Exception("You have to install this app first", 1);
                // redirect(url('/install'))->send();
            }

            /* get application setting */
            $site = getSiteConfig();
            if (!$site) {
                throw new \Exception("Site config not found", 1);
            }

            /* set application setting to global */
            $this->app->singleton('site', function () use ($site) {
                return $site;
            });

            /* set user to global */
            $this->app->singleton('user', function () {
                return \Auth::user();
            });

            /* set config template */
            $theme = $this->getTemplate();
            $themeConfig = resource_path('views/' . $theme . '/config.php');
            $config_template = file_exists($themeConfig) ? include $themeConfig : [];
            app('config')->set('site', $config_template);

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

        /* share admin panel to global */
        $this->app->singleton('admin_path', function () {
            return adminPath();
        });
        $this->app->singleton('is_admin_subdomain', function () {
            return isAdminSubdomain();
        });
        $this->app->singleton('is_admin_panel', function () {
            return isAdminPanel();
        });

        /* set middleware */
        // $this->loadMiddleware();

        /* load zetthcore */
        $this->loadRoutesFrom($this->package_path . '/src/routes/routes.php');
        $this->loadViewsFrom($this->package_path . '/src/resources/views', 'zetthcore');
        $this->loadMigrationsFrom($this->package_path . '/src/database/migrations');
        // $this->loadSeedsFrom($this->package_path . '/src/database/seeds');

        $this->publishAll();

        /* set default varchar */
        // Schema::defaultStringLength(255);

        /* set default timezone */
        // date_default_timezone_set(app('site')->timezone);
        // config(['app.timezone' => app('site')->timezone]);
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

    public function loadMiddleware()
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('access', \ZetthCore\Http\Middleware\AccessMiddleware::class);
        $router->aliasMiddleware('site', \ZetthCore\Http\Middleware\SiteMiddleware::class);
        $router->aliasMiddleware('visitor_log', \ZetthCore\Http\Middleware\VisitorLogMiddleware::class);

        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\InlineCss::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\ElideAttributes::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\InsertDNSPrefetch::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveComments::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class);
    }

    public function publishAll()
    {
        $publishable_path = $this->package_path . '/publishable';

        $this->publishes([
            $publishable_path . '/Kernel.php' => app_path('Http/Kernel.php'),
        ], 'zetthkernel');

        /* $this->publishes([
        __DIR__ . '/../../database' => database_path(),
        ], 'zetthmigrate'); */

        $this->publishes([
            $publishable_path . '/config/app.php' => config_path('app.php'),
            $publishable_path . '/config/auth.php' => config_path('auth.php'),
            $publishable_path . '/config/database.php' => config_path('database.php'),
            $publishable_path . '/config/image.php' => config_path('image.php'),
            $publishable_path . '/config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
            $publishable_path . '/config/laratrust.php' => config_path('laratrust.php'),
        ], 'zetthconfig');

        $this->publishes([
            $publishable_path . '/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
        ], 'zetthhandler');

        $this->publishes([
            $publishable_path . '/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            $publishable_path . '/Middleware/RedirectIfAuthenticated.php' => app_path('Http/Middleware/RedirectIfAuthenticated.php'),
        ], 'zetthmiddleware');

        /* $this->publishes([
    $publishable_path . '/routes/web.php' => base_path('routes/web.php'),
    ], 'zetthroutes'); */
    }
}
