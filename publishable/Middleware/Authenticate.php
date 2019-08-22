<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $current_url = url()->current();
            $is_admin = strpos($current_url, 'admin');
            if ($is_admin) {
                return route('admin.login.form');
            }

            return route('root');
        }
    }
}
