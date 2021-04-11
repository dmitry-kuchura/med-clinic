<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    private DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function list(): JsonResponse
    {
        $result = $this->doctorService->list();

        return $this->returnResponse(['result' => $result]);
    }

    public function approved(): JsonResponse
    {
        $result = $this->doctorService->approvedList();

        return $this->returnResponse(['result' => $result]);
    }

    public function search(Request $request): JsonResponse
    {
        $result = $this->doctorService->search($request->get('query'));

        return $this->returnResponse([
            'result' => $result
        ]);
    }

    public function info($id): JsonResponse
    {
        $result = $this->doctorService->find($id);

        return $this->returnResponse(['result' => $result]);
    }
}
