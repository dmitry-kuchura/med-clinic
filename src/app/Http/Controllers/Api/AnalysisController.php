<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateTestRequest;
use App\Services\AnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnalysisController extends Controller
{
    private AnalysisService $service;

    public function __construct(AnalysisService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request): JsonResponse
    {
        $patientId = $request->route('patientId');

        $result = $this->service->list($patientId);

        return $this->returnResponse(['result' => $result]);
    }

    public function create(PatientCreateTestRequest $request): JsonResponse
    {
        $this->service->create($request->all());

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }
}
