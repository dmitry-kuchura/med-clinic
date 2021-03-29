<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateRequest;
use App\Http\Requests\Patients\PatientUpdateRequest;
use App\Services\PatientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PatientsController extends Controller
{
    private PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function list(): JsonResponse
    {
        $result = $this->patientService->list();

        return $this->returnResponse(['result' => $result]);
    }

    public function info($id): JsonResponse
    {
        $result = $this->patientService->find($id);

        return $this->returnResponse(['result' => $result]);
    }

    public function create(PatientCreateRequest $request): JsonResponse
    {
        $this->patientService->create($request->all());

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function update(PatientUpdateRequest $request): JsonResponse
    {
        $this->patientService->update($request->all());

        return $this->returnResponse(['updated' => true]);
    }

    public function search(Request $request): JsonResponse
    {
        $result = $this->patientService->search($request->get('query'));

        return $this->returnResponse([
            'result' => $result
        ]);
    }
}
