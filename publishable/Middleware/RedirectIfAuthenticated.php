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
                return redirect(_url(route('admin.dashboard.index')));
            }
            if (bool(Auth::user()->is_first_login)) {
                return redirect(_url(route('web.user.edit-profile', ['user' => Auth::user()->name])));
            } else {
                return redirect(_url(route('web.user.index', ['user' => Auth::user()->name])));
            }

            return redirect(_url(route('web.root')));
        }

        return $next($request);
    }
}
