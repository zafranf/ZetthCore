<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        // $this->errorLog($e);

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            $theme = $this->getTemplate();
            if (!app()->runningInConsole() && isAdminPanel()) {
                $theme = 'zetthcore::AdminSC';
            }
            if (view()->exists($theme . '.errors.' . $e->getStatusCode())) {
                return response()->view($theme . '.errors.' . $e->getStatusCode(), [
                    'breadcrumbs' => [
                        [
                            'page' => 'Beranda',
                            'icon' => '',
                            'url' => _url('/'),
                        ], [
                            'page' => $e->getStatusCode(),
                            'icon' => '',
                            'url' => '',
                        ],
                    ],
                    'status_code' => $e->getStatusCode(),
                ], $e->getStatusCode());
            }
        } else if ($e instanceof ModelNotFoundException) {
            abort(404);
        }

        return parent::render($request, $e);
    }
}
