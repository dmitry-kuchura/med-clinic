<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AmazonS3;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateTestRequest;
use App\Mail\AddPatientTestMail;
use App\Services\AnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

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
        $file = null;

        $data = $request->all();

        if ($request->file('file')) {
            $file = $request->file('file');

            $uploadService = new AmazonS3();
            $fileName = $uploadService->upload($request, $request->get('patient_id'));

            $data['file'] = $fileName;
        }

        $analysis = $this->service->create($data);

        Mail::to($analysis->patient->user->email)->send(new AddPatientTestMail($file));

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }
}
