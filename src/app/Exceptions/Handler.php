<?php

namespace App\Exceptions;

use App\Services\AppointmentsService;
use App\Services\DoctorsService;
use App\Services\LogService;
use App\Services\PatientsService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

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

    /** @var LogService */
    private LogService $logService;

    public function __construct(Container $container, LogService $logService)
    {
        parent::__construct($container);
        $this->logService = $logService;
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $this->logService->exception($e->getMessage());

        if ($e instanceof ModelNotFoundException && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Not Found!'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof NotAddPatientTestException && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        if (($e instanceof UpdateDoctorException || $e instanceof UpdatePatientException) && $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        return parent::render($request, $e);
    }
}
