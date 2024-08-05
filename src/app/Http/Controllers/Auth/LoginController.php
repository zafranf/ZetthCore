<?php

namespace ZetthCore\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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

    use AuthenticatesUsers {
        login as loginTrait;
    }

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
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $r
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $r)
    {
        $password = $r->input('password');
        /* save activity */
        $this->activityLog('<b>' . $r->input($this->username(true)) . '</b> mencoba masuk ke halaman admin');

        /* merge password */
        $r->merge([
            $this->username() => _encrypt($r->input($this->username())),
            'password' => $password . \Str::slug(config('app.key')),
        ]);

        return $this->loginTrait($r);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $r)
    {
        /* merge decrypted username */
        $r->merge([
            $this->username() => _decrypt($r->input($this->username())),
        ]);

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed-admin')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username($merge = false)
    {
        /* set variable */
        $key = 'name';
        $value = request()->input($key);

        /* check input email */
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $key = 'email';
        }

        /* merge request */
        if ($merge) {
            request()->merge([$key => $value]);
        }

        return $key;
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

            /* rollback, merge decrypted username */
            $r->merge([
                $this->username() => _decrypt($r->input($this->username())),
            ]);

            return redirect(adminPath() . '/login')->withInput()->withErrors([
                $this->username() => trans('auth.failed-notadmin'),
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
