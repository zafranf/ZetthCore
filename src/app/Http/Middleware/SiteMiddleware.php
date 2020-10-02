<?php
namespace ZetthCore\Http\Middleware;

use Closure;

class SiteMiddleware
{
    use \ZetthCore\Traits\MainTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* check date */
        $this->check_date();

        /* set uri */
        $uri = $request->route()->uri();
        $current = url()->current();
        $status = app('site')->status;

        /* next on subscribe */
        if (($request->isMethod('post') && $current == route('web.action.subscribe')) || (config('app.env') != 'production' && $uri == 'test')) {
            return $next($request);
        }

        /* check status */
        if ($status == 'comingsoon') {
            if ($uri != config('path.comingsoon')) {
                return redirect(route('web.comingsoon'));
            }
        } else if ($status == 'maintenance') {
            if ($uri != config('path.maintenance')) {
                return redirect(route('web.maintenance'));
            }
        } else if ($status == 'suspend') {
            return abort(503);
        } else {
            if ($uri == config('path.maintenance') || $uri == config('path.maintenance')) {
                return redirect(route('web.root'));
            }
        }

        return $next($request);
    }

    public function check_date()
    {
        if (app('site')->status == 'suspend' || app('site')->status == 'active') {
            return false;
        }

        /* compare time */
        $active_at = app('site')->active_at;
        if (now()->greaterThanOrEqualTo($active_at)) {
            /* set active */
            $setting = app('site');
            $setting->status = 'active';
            $setting->save();

            /* clear cache */
            \Cache::flush();

            /* send notif to subscriber */
            \ZetthCore\Jobs\Launch::dispatch();
        }
    }

}
