<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;

class PatientAppointmentsController extends Controller
{
    private AppointmentService $service;

    public function __construct(AppointmentService $service)
    {
        $this->service = $service;
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->list($id);

        return $this->returnResponse(['result' => $result]);
    }
}
