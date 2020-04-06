<?php

namespace ZetthCore\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use ZetthCore\Http\Controllers\AdminController;

class LoginController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $data = [
            'current_url' => adminPath() . '/login',
        ];

        return view('zetthcore::AdminSC.auth.login', $data);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        /* set variable */
        $field = 'name';
        $name = request()->get($field);

        /* check input email */
        if (filter_var($name, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        /* merge request */
        request()->merge([$field => $name]);

        return $field;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $r)
    {
        /* clear session * attempts */
        $r->session()->regenerate();
        $this->clearLoginAttempts($r);

        return $this->authenticated($r, $this->guard()->user())
        ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $r
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $r, $user)
    {
        if (!app('user')->is_admin) {
            \Auth::logout();

            return redirect(adminPath() . '/login')->withInput()->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
        }

        /* set redirect for user admin */
        $this->redirectTo = adminPath() . '/dashboard';

        /* save activity */
        $this->activityLog('[~name] (' . $this->getUserRoles() . ') masuk halaman admin');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $r)
    {
        /* set redirect */
        $redirect = '/';
        if (app('user')->is_admin) {
            $redirect = adminPath() . '/login';
        }

        /* save activity */
        $this->activityLog('[~name] keluar dari halaman admin');

        /* clear session */
        $this->guard()->logout();
        $r->session()->invalidate();

        return redirect($redirect);
    }
}
