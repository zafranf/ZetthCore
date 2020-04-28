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
        $site = null;
        $this->package_path = base_path('vendor/zafranf/zetthcore');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \ZetthCore\Console\Commands\Install::class,
                \ZetthCore\Console\Commands\Reinstall::class,
            ]);
        } else {
            /* check config */
            if (!$this->checkDBConnection() || ($this->checkDBConnection() && !\Schema::hasTable('sites'))) {
                /* sementara, nanti redirect ke halaman install */
                throw new \Exception("You have to install this app first", 1);
                // redirect(url('/install'))->send();
            }
        }

        /* get application setting */
        if (\Schema::hasTable('sites')) {
            $site = getSiteConfig();
            if (!$site && !$this->app->runningInConsole()) {
                throw new \Exception("Site config not found", 1);
            }
        }

        /* set application setting to global */
        $this->app->singleton('site', function () use ($site) {
            return $site;
        });

        /* set user to global */
        $this->app->singleton('user', function () use ($site) {
            return \Cache::remember('auth_user.' . ($site->id ?? null), getCacheTime(), function () {
                return \Auth::user() ? \Auth::user()->load('detail') : null;
            });
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
            $publishable_path . '/config/cache.php' => config_path('cache.php'),
            $publishable_path . '/config/database.php' => config_path('database.php'),
            $publishable_path . '/config/image.php' => config_path('image.php'),
            $publishable_path . '/config/imagecache.php' => config_path('imagecache.php'),
            $publishable_path . '/config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
            $publishable_path . '/config/laratrust.php' => config_path('laratrust.php'),
        ], 'zetthconfig');

        $this->publishes([
            $publishable_path . '/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
        ], 'zetthhandler');

        $this->publishes([
            $publishable_path . '/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            $publishable_path . '/Middleware/RedirectIfAuthenticated.php' => app_path('Http/Middleware/RedirectIfAuthenticated.php'),
            $publishable_path . '/Middleware/TrimDomains.php' => app_path('Http/Middleware/TrimDomains.php'),
        ], 'zetthmiddleware');

        /* $this->publishes([
    $publishable_path . '/routes/web.php' => base_path('routes/web.php'),
    ], 'zetthroutes'); */
    }
}
