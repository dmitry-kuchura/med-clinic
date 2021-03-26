<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateRequest;
use App\Http\Requests\Patients\PatientUpdateRequest;
use App\Services\PatientService;
use Illuminate\Http\Response;

class PatientsController extends Controller
{
    private PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    public function list()
    {
        $result = $this->patientService->list();

        return $this->returnResponse(['result' => $result]);
    }

    public function info($id)
    {
        $result = $this->patientService->find($id);

        return $this->returnResponse(['result' => $result]);
    }

    public function create(PatientCreateRequest $request)
    {
        $this->patientService->create($request->all());

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function update(PatientUpdateRequest $request)
    {
        $this->patientService->update($request->all());

        return $this->returnResponse(['updated' => true], Response::HTTP_OK);
    }
}
