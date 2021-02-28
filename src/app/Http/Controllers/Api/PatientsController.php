<?php

namespace App\Http\Controllers\Api;

use App\Actions\PatientAction;
use App\Helpers\AmazonS3;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patients\PatientAddTestRequest;
use App\Http\Requests\Patients\PatientCreateRequest;
use App\Http\Requests\Patients\PatientUpdateRequest;
use App\Mail\AddPatientTestMail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class PatientsController extends Controller
{
    private PatientAction $patientAction;

    public function __construct(PatientAction $patientAction)
    {
        $this->patientAction = $patientAction;
    }

    public function list()
    {
        $result = $this->patientAction->list();

        return $this->returnResponse(['result' => $result]);
    }

    public function info($id)
    {
        $result = $this->patientAction->info($id);

        return $this->returnResponse(['result' => $result]);
    }

    public function create(PatientCreateRequest $request)
    {
        $this->patientAction->create($request->all());

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function update(PatientUpdateRequest $request)
    {
        $this->patientAction->update($request->all());

        return $this->returnResponse(['updated' => true], Response::HTTP_OK);
    }

    public function addTest(PatientAddTestRequest $request)
    {
        $file = null;

        $data = $request->all();

        if ($request->file('file')) {
            $file = $request->file('file');

            $uploadService = new AmazonS3();
            $fileName = $uploadService->upload($request, $request->get('patient_id'));

            $data['file'] = $fileName;
        }

        $patientTest = $this->patientAction->addPatientTest($data);


        Mail::to($patientTest->patient->user->email)->send(new AddPatientTestMail($patientTest, $file));

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function listTest($id)
    {
        $result = $this->patientAction->listPatientTests($id);

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }
}
