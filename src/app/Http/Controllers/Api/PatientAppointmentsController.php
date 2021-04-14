<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppointmentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientAppointmentsController extends Controller
{
    private AppointmentsService $service;

    public function __construct(AppointmentsService $service)
    {
        $this->service = $service;
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->list($id);

        return $this->returnResponse(['result' => $result]);
    }

    public function today(): JsonResponse
    {
        $result = $this->service->today();

        return $this->returnResponse([
            'result' => $result
        ]);
    }
}
