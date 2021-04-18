<?php

namespace App\Http\Controllers\Api;

use App\Actions\PatientTestsAction;
use App\Helpers\AmazonS3;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientCreateTestRequest;
use App\Mail\AddPatientTestMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class PatientTestsController extends Controller
{
    private PatientTestsAction $patientTestsAction;

    public function __construct(PatientTestsAction $patientTestsAction)
    {
        $this->patientTestsAction = $patientTestsAction;
    }

    public function list($id)
    {
        $result = $this->patientTestsAction->listPatientTests($id);

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }

    public function create(PatientCreateTestRequest $request)
    {
        $file = null;

        $data = $request->all();

        if ($request->file('file')) {
            $file = $request->file('file');

            $uploadService = new AmazonS3();
            $fileName = $uploadService->upload($request, $request->get('patient_id'));

            $data['file'] = $fileName;
        }

        $patientTest = $this->patientTestsAction->addPatientTest($data);


        Mail::to($patientTest->patient->user->email)->send(new AddPatientTestMail($patientTest, $file));

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }
}
