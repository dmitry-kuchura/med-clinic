<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;

class LogsController extends Controller
{
    private LogService $service;

    public function __construct(LogService $service)
    {
        $this->service = $service;
    }

    public function list(): JsonResponse
    {
        $result = $this->service->list();

        return $this->returnResponse(['result' => $result]);
    }
}
