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
        if (count($xname) > 1) {
            if ($xname[1] == "index") {
                $access = 'index';
            } else if ($xname[1] == "create" || $xname[1] == "store") {
                $access = 'create';
            } else if ($xname[1] == "show") {
                $access = 'read';
            } else if ($xname[1] == "edit" || $xname[1] == "update") {
                $access = 'update';
            } else if ($xname[1] == "destroy") {
                $access = 'delete';
            } else {
                throw new Exception('Undefined access');
            }
        }

        return $user->can($access . '-' . $xname[0]);
    }

}
