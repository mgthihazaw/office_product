<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        // if ($request->wantsJson()) {
        //     if ($exception instanceof AuthenticationException) {
        //         return response()->json('Authentication Required');
        //     }
        //     if ($exception instanceof TokenInvalidException) {
        //         return response()->json(['error' => 'Token is Invalid', 'type' => 'token_invalid'], 403);
        //     } else if ($exception instanceof TokenExpiredException) {
        //         return response()->json(['error' => 'Token is Expired', 'type' => 'token_expired'], 403);
        //     } else if ($exception instanceof JWTException) {
        //         return response()->json(['error' => 'Unauthenticated'], 401);
        //     }
        //     return response()->json(['error' => $exception->getMessage()], 500);
        // }

        return parent::render($request, $exception);
    }
}