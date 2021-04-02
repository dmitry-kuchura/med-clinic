<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Firebird\PatientVisitRepository;
use App\Services\AppointmentService;

class FirebirdController extends Controller
{
    /** @var PatientVisitRepository */
    private PatientVisitRepository $patientVisitRepository;

    /** @var AppointmentService */
    private AppointmentService $appointmentService;

    public function __construct(
        AppointmentService $appointmentService,
        PatientVisitRepository $patientVisitRepository
    )
    {
        $this->appointmentService = $appointmentService;
        $this->patientVisitRepository = $patientVisitRepository;
    }

    public function list()
    {

    }
}
