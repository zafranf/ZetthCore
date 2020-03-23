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
        $status = app('site')->status;

        /* next on subscribe */
        if ($request->isMethod('post') && $uri == "subscribe") {
            return $next($request);
        }

        /* check status */
        if ($status == 0) {
            if ($uri != "comingsoon") {
                return redirect(route('web.comingsoon'));
            }
        } else if ($status == 2) {
            if ($uri != "maintenance") {
                return redirect(route('web.maintenance'));
            }
        } else {
            if ($uri == "maintenance" || $uri == "comingsoon") {
                return redirect(route('web.root'));
            }
        }

        return $next($request);
    }

    public function check_date()
    {
        $now = time();
        $active_at = app('site')->active_at->getTimestamp();
        if ($now >= $active_at) {
            $setting = app('site');
            $setting->status = 1;
            $setting->save();

            return true;
        }

        return false;
    }

}
