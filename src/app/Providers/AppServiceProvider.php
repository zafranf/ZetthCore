<?php

namespace ZetthCore\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use \ZetthCore\Traits\MainTrait;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /* check admin page */
        $adminPath = '/' . env('ADMIN_PATH', 'admin');
        $isAdminSubdomain = false;
        $isAdminPanel = false;

        /* check config */
        if (!$this->app->runningInConsole()) {
            try {
                \DB::getPdo();
            } catch (\Exception $e) {
                /* sementara, nanti redirect ke halaman install */
                throw new \Exception("You have to install this app first", 1);
                // redirect(url('/install'))->send();
            }

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

            /* get application setting */
            $site = \ZetthCore\Models\Site::where('domain', $host)->with('socmed_data', 'socmed_data.socmed')->first();
            if (!$site) {
                throw new \Exception("Site config not found", 1);
            }

            /* set application setting to global */
            $this->app->singleton('site', function () use ($site) {
                return $site;
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
        $this->app->singleton('admin_path', function () use ($adminPath) {
            return $adminPath;
        });
        $this->app->singleton('is_admin_subdomain', function () use ($isAdminSubdomain) {
            return $isAdminSubdomain;
        });
        $this->app->singleton('is_admin_panel', function () use ($isAdminPanel) {
            return $isAdminPanel;
        });

        /* set middleware */
        $this->loadMiddleware();
        $this->loadRoutesFrom(__DIR__ . '/../../routes/routes.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'zetthcore');
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        // $this->loadSeedsFrom(__DIR__ . '/../../database/seeds');

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
        // $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\TrimUrls::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\RemoveQuotes::class);
        $router->pushMiddlewareToGroup('web', \RenatoMarinho\LaravelPageSpeed\Middleware\CollapseWhitespace::class);
    }

    public function publishAll()
    {
        /* $this->publishes([
        __DIR__ . '/../../database' => database_path(),
        ], 'zetthmigrate'); */
        $publishable_path = '/../../../publishable';
        $this->publishes([
            __DIR__ . $publishable_path . '/config/app.php' => config_path('app.php'),
            __DIR__ . $publishable_path . '/config/auth.php' => config_path('auth.php'),
            __DIR__ . $publishable_path . '/config/database.php' => config_path('database.php'),
            __DIR__ . $publishable_path . '/config/image.php' => config_path('image.php'),
            __DIR__ . $publishable_path . '/config/laratrust.php' => config_path('laratrust.php'),
            __DIR__ . $publishable_path . '/config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
        ], 'zetthconfig');
        /* $this->publishes([
        __DIR__ . $publishable_path.'/config/auth.php' => config_path('auth.php'),
        ], 'zetthauth'); */
        $this->publishes([
            __DIR__ . $publishable_path . '/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
        ], 'zetthhandler');
        $this->publishes([
            __DIR__ . $publishable_path . '/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            __DIR__ . $publishable_path . '/Middleware/RedirectIfAuthenticated.php' => app_path('Http/Middleware/RedirectIfAuthenticated.php'),
        ], 'zetthmiddleware');
        /* $this->publishes([
    __DIR__ . $publishable_path . '/routes/web.php' => base_path('routes/web.php'),
    ], 'zetthroutes'); */
    }
}
