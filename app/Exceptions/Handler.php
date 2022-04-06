<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            $this->renderable(function (ValidationException $exception) {
                $response = [
                    'status' => 'error',
                    'message' => $exception->validator->errors()
                ];
                return response()->json($response, 400);
            });

            $this->renderable(function (NotFoundHttpException $exception) {
                $response = [
                    'status' => 'error',
                    'message' => 'Data not found',
                ];
                return response()->json($response, 404);
            });
        });
    }
}
