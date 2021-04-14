<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DoctorsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DoctorsController extends Controller
{
    private DoctorsService $doctorService;

    public function __construct(DoctorsService $doctorService)
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

    public function approve(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $this->doctorService->addApprove($id);

        return $this->returnResponse(['added' => true], Response::HTTP_CREATED);
    }

    public function approveDelete(Request $request): JsonResponse
    {
        $id = $request->get('id');

        $this->doctorService->deleteApprove($id);

        return $this->returnResponse(['deleted' => true]);
    }
}
