<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use Illuminate\Http\JsonResponse;

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
}
