<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VisitsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientsVisitsController extends Controller
{
    private VisitsService $service;

    public function __construct(VisitsService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request): JsonResponse
    {
        $patientId = $request->route('patientId');

        $result = $this->service->getPatientsVisitsList($patientId);

        return $this->returnResponse(['result' => $result]);
    }

    public function approvedList(): JsonResponse
    {
        $list = $this->service->getApprovedVisitsList();
        $approved = $this->service->getAllApprovedPatientVisitTemplateList();

        return $this->returnResponse([
            'list' => $list,
            'approved' => $approved
        ]);
    }

    public function addApproved(Request $request): JsonResponse
    {
        $this->service->addApprovedPatientVisitTemplateList($request->get('template'));

        return $this->returnResponse([
            'success' => true
        ]);
    }

    public function deleteApproved(Request $request): JsonResponse
    {
        $this->service->deleteApprovedPatientVisitTemplateList($request->get('template'));

        return $this->returnResponse([
            'success' => true
        ]);
    }
}
