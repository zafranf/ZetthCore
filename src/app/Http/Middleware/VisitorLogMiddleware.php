<?php

namespace ZetthCore\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VisitorLogMiddleware
{
    use \ZetthCore\Traits\MainTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get')) {
            $this->visitorLog();
            $this->intermLog($request);
        }

        return $next($request);
    }

    public function visitorLog()
    {
        /* prevent log if not get && head */
        if (!in_array(strtoupper(\Request::method()), ['GET', 'HEAD'])) {
            return true;
        }

        /* set variable */
        $ip = getUserIP();
        $page = isAdminSubdomain() ? \Request::fullUrl() : (\Request::getRequestUri() ?? '-');
        $referrer = \Request::server('HTTP_REFERER') ?? null;
        $referrer = str_replace(_url('/'), "", $referrer);
        $agent = new \Jenssegers\Agent\Agent();
        $ua = $agent->getUserAgent() ?? null;
        $browser = $agent->browser() ?? null;
        $browser_version = !is_null($browser) ? $agent->version($browser) : null;
        $device = null;
        if ($agent->isPhone()) {
            $device = 'phone';
        } else if ($agent->isTablet()) {
            $device = 'tablet';
        } else if ($agent->isDesktop()) {
            $device = 'desktop';
        }
        $device_name = $device != '' ? $agent->device() : null;
        $os = $agent->platform() ?? null;
        $os_version = !is_null($os) ? $agent->version($os) : null;
        $is_robot = $agent->isRobot() ? 'yes' : 'no';
        $robot_name = bool($is_robot) ? $agent->robot() : null;
        $time = carbon()->format('Y-m-d H');
        $site_id = app('site')->id;

        /* generate id */
        $id = md5(session()->getId() . $ip . $page . $referrer . $ua . $browser . $browser_version . $device . $device_name . $os . $os_version . $is_robot . $robot_name . $time . $site_id);

        /* save log */
        \ZetthCore\Jobs\VisitorLog::dispatch(
            $id,
            [
                'ip' => $ip,
                'page' => $page,
                'referral' => $referrer,
                'agent' => $ua,
                'browser' => $browser,
                'browser_version' => $browser_version,
                'device' => $device,
                'device_name' => $device_name,
                'os' => $os,
                'os_version' => $os_version,
                'is_robot' => $is_robot,
                'robot_name' => $robot_name,
                'count' => \DB::raw('count+1'),
                'site_id' => $site_id,
            ]
        );
    }

    public function intermLog(Request $r)
    {
        /* check referrer */
        $referrer = _server('HTTP_REFERER') ?? null;
        if ($referrer && !empty($r->query())) {
            /* get host from referrer */
            $host = parse_url($referrer)['host'];

            /* get interm host */
            $interm = \Cache::remember('intermlog.' . app('site')->id, getCacheTime(), function () use ($host) {
                return \App\Models\Interm::where('host', $host)->first();
            });
            if ($interm) {
                /* get host param */
                $param = $interm->param;

                /* get keyword from query string */
                $query = explode($param . "=", $referrer)[1] ?? null;
                if (!is_null($query)) {
                    /* get real keyword */
                    $keyword = explode("&", $query)[0] ?? null;

                    /* get post id */
                    $url = url()->current();
                    $page = str_replace(_url('/'), '', $url);
                    $slug = explode('/', $page);
                    $post = \Cache::remember('intermlogpost.' . app('site')->id, getCacheTime(), function () use ($slug) {
                        return \App\Models\Post::active()->where('slug', end($slug))->first();
                    });

                    /* save keyword */
                    $this->saveInterm($host, urldecode($keyword), $post->id ?? null);
                }

                /* save keyword */
                if ($r->input('q')) {
                    $this->saveInterm($host, $r->input('q'), $post->id ?? null);
                }
            }
        }
    }

    private function saveInterm($host, $keyword, $post_id)
    {
        /* save keyword */
        \App\Models\IntermData::create([
            'host' => $host,
            'keyword' => $keyword,
            'post_id' => $post_id ?? null,
            'site_id' => app('site')->id,
            'count' => 1,
        ]);
    }
}
