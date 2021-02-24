<?php

namespace App\Http\Controllers\Api;

use App\Actions\PatientAction;
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
        $patientTest = $this->patientAction->addPatientTest($request->all());

//        Mail::to($patientTest->patient->user->email)->send(new AddPatientTestMail($patientTest));

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function listTest($id)
    {
        $result = $this->patientAction->listPatientTests($id);

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }
}
