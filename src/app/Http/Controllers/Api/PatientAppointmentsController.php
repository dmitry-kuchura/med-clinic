<?php

namespace App\Http\Controllers\Api;

use App\Facades\AppointmentFacade;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PatientAppointmentsController extends Controller
{
    private AppointmentFacade $facade;

    public function __construct(AppointmentFacade $facade)
    {
        $this->facade = $facade;
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->facade->list($id);

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }
}
