<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class Handler extends ExceptionHandler
{
    use \ZetthCore\Traits\MainTrait;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $e
     * @return void
     */
    public function report(Throwable $e)
    {
        $code = $e->getCode() ?? null;
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
            $code = $e->getStatusCode();
        }

        // $this->errorLog($e);
        if ($e->getMessage() != 'Unauthenticated.') {
            $agent = new \Jenssegers\Agent\Agent();
            $is_robot = $agent->isRobot() ? 'yes' : 'no';

            Log::debug([
                'code' => $code,
                'ip' => getUserIP(),
                'is_robot' => $is_robot,
                'robot_name' => bool($is_robot) ? $agent->robot() : null,
                'browser' => $agent->browser() ?? null,
                'user' => [
                    'id' => Auth::user()->id ?? null,
                    'name' => Auth::user()->fullname ?? null
                ],
                'method' => Request::method(),
                'url' => Request::fullUrl() ?? null,
                'input' => Request::except(['password', 'password_confirmation', 'captured']) ?? null,
                'files' => Request::allFiles() ?? null,
                'exception_message' => $e->getMessage()
            ]);
        }

        /* redirect telescope to 404 */
        if (req()->path() == 'telescope' && $code == 403) {
            abort(404, 'The route telescope could not be found.');
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            abort(404);
        } else if ($this->isHttpException($e)) {
            $code = $this->isHttpException($e) ? $e->getStatusCode() : $e->getCode();
            if ($code == 0) {
                $code = 500;
            }

            $theme = $this->getTemplate();
            if (!app()->runningInConsole() && isAdminPanel()) {
                $theme = 'zetthcore::AdminSC';
            }
            if (view()->exists($theme . '.errors.' . $code)) {
                return response()->view($theme . '.errors.' . $code, [
                    'breadcrumbs' => [
                        [
                            'page' => 'Beranda',
                            'icon' => '',
                            'url' => _url('/'),
                        ],
                        [
                            'page' => $code,
                            'icon' => '',
                            'url' => '',
                        ],
                    ],
                    'status_code' => $code,
                ], $code);
            }
        }

        return parent::render($request, $e);
    }
}
