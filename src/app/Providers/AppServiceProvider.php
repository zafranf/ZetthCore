<?php

namespace ZetthCore\Providers;

use Illuminate\Console\Scheduling\Schedule;
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
                \ZetthCore\Console\Commands\Link::class,
                \ZetthCore\Console\Commands\Site::class,
            ]);
        } else {
            /* check config */
            if (!$this->checkDBConnection() || ($this->checkDBConnection() && !\Schema::hasTable('sites'))) {
                throw new \Exception("You have to install this app first", 1);
            }
        }

        /* get application setting */
        if ($this->checkDBConnection()) {
            if (\Schema::hasTable('sites')) {
                $site = getSiteConfig();
                if (!$site && !$this->app->runningInConsole()) {
                    throw new \Exception("Site config not found", 1);
                }
            }
        }

        /* set application setting to global */
        $this->app->singleton('site', function () use ($site) {
            return $site;
        });

        /* set user to global */
        $this->app->singleton('user', function () {
            return \Cache::remember('auth_user.' . session()->getId(), getCacheTime(), function () {
                return \Auth::user() ? \Auth::user()->load('detail') : null;
            });
        });

        /* set locale */
        \App::setLocale(app('user')->lang ?? (app('site')->lang ?? 'id'));

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

        /* load zetthcore */
        $this->loadRoutesFrom($this->package_path . '/src/routes/routes.php');
        $this->loadViewsFrom($this->package_path . '/src/resources/views', 'zetthcore');
        $this->loadMigrationsFrom($this->package_path . '/src/database/migrations');

        /* publish files */
        $this->publishAll();

        /* check site status */
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('site:check-status')->everyMinute();
        });

        /* force https */
        if (bool(config('app.force_https'))) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
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
            $publishable_path . '/config/filesystems.php' => config_path('filesystems.php'),
            $publishable_path . '/config/image.php' => config_path('image.php'),
            $publishable_path . '/config/laratrust_seeder.php' => config_path('laratrust_seeder.php'),
            $publishable_path . '/config/laratrust.php' => config_path('laratrust.php'),
            $publishable_path . '/config/laravel-page-speed.php' => config_path('laravel-page-speed.php'),
            $publishable_path . '/config/logging.php' => config_path('logging.php'),
            $publishable_path . '/config/mail.php' => config_path('mail.php'),
            $publishable_path . '/config/path.php' => config_path('path.php'),
            $publishable_path . '/config/queue.php' => config_path('queue.php'),
            $publishable_path . '/config/services.php' => config_path('services.php'),
            $publishable_path . '/config/translation-loader.php' => config_path('translation-loader.php'),
        ], 'zetthconfig');

        $this->publishes([
            $publishable_path . '/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
        ], 'zetthhandler');

        $this->publishes([
            $publishable_path . '/Middleware/Authenticate.php' => app_path('Http/Middleware/Authenticate.php'),
            $publishable_path . '/Middleware/RedirectIfAuthenticated.php' => app_path('Http/Middleware/RedirectIfAuthenticated.php'),
            $publishable_path . '/Middleware/TrimDomains.php' => app_path('Http/Middleware/TrimDomains.php'),
        ], 'zetthmiddleware');

        $this->publishes([
            $publishable_path . '/routes/web.php' => base_path('routes/web.php'),
        ], 'zetthroutes');
    }
}
