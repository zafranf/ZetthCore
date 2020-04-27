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
        $this->visitorLog();
        $this->intermLog($request);

        return $next($request);
    }

    public function intermLog(Request $r)
    {
        /* check referrer */
        $referrer = isset(_server('HTTP_REFERER')) ? _server('HTTP_REFERER') : null;
        if ($referrer) {
            /* get host from referrer */
            $host = parse_url($referrer)['host'];

            /* get interm host */
            $interm = \App\Models\Interm::where('host', $host)->first();
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
                    $page = str_replace(url('/'), '', $url);
                    $slug = explode('/', $page);
                    $post = \App\Models\Post::where('slug', end($slug))->first();

                    /* save keyword */
                    \App\Models\IntermData::updateOrCreate([
                        'host' => $host,
                        'keyword' => $keyword,
                        'post_id' => $post->id ?? null,
                        'site_id' => app('site')->id,
                    ], [
                        'count' => \DB::raw('count+1'),
                    ]);
                }
            }
        }
    }

}
