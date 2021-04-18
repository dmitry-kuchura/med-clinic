<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VisitsService;
use Illuminate\Http\JsonResponse;

class PatientsVisitsController extends Controller
{
    private VisitsService $service;

    public function __construct(VisitsService $service)
    {
        $this->service = $service;
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->getPatientsVisitsList($id);

        return $this->returnResponse(['result' => $result]);
    }
}
