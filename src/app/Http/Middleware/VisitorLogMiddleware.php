<?php
namespace ZetthCore\Http\Middleware;

use Closure;

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
    public function handle($request, Closure $next)
    {
        $this->visitorLog();

        return $next($request);
    }

}
