<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return parent::render($request, $exception);
        }

        $status = 500;
        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
        } elseif ($exception instanceof ModelNotFoundException) {
            $status = 404;
        } elseif ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
            $status = 403;
        } elseif ($exception instanceof ValidationException) {
            $status = 422;
        }

        $view = view()->exists('errors.' . $status) ? 'errors.' . $status : 'errors.500';
        $message = match ($status) {
            403 => 'Anda tidak memiliki akses ke halaman ini.',
            404 => 'Halaman yang Anda cari tidak ditemukan.',
            419 => 'Sesi keamanan sudah berakhir. Silakan muat ulang halaman dan coba lagi.',
            422 => 'Data yang dikirim belum dapat diproses.',
            429 => 'Terlalu banyak permintaan. Silakan coba lagi beberapa menit lagi.',
            default => 'Terjadi kendala pada server. Silakan coba lagi nanti.',
        };

        return response(view($view, [
            'status' => $status,
            'message' => $message,
            'baseUrl' => rtrim((string) config('app.url'), '/'),
        ]), $status);
    }
}
