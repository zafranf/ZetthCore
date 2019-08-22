<?php
namespace ZetthCore\Http\Middleware;

use Closure;

class AccessMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->checkAccess()) {
            throw new \Exception('Forbidden access', 403);
        }

        return $next($request);
    }

    public function checkAccess()
    {
        /* get user login */
        $user = \Auth::user();
        if (!$user) {
            throw new \Exception('There are no user in current session');
        }

        /* get route name */
        $name = \Route::current()->getName();
        $xname = explode('.', $name);

        /* check access */
        $access = null;
        $page = null;
        if (count($xname) > 1) {
            $access = end($xname);
            if ($access == "index") {
                $access = 'index';
            } else if ($access == "create" || $access == "store") {
                $access = 'create';
            } else if ($access == "show") {
                $access = 'read';
            } else if ($access == "edit" || $access == "update") {
                $access = 'update';
            } else if ($access == "destroy") {
                $access = 'delete';
            } else {
                throw new \Exception('Undefined access');
            }

            $page = $xname[0];
            if ($page == 'admin') {
                $page = $xname[1];
            }
        }

        return $user->can($access . '-' . $page);
    }

}
