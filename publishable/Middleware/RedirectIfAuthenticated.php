<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if (Auth::user()->is_admin && isAdminPanel()) {
                return redirect(route('admin.dashboard.index'));
            }
            if (Auth::user()->is_first_login) {
                return redirect(route('web.profile.edit'));
            }

            return redirect(route('web.root'));
        }

        return $next($request);
    }
}
