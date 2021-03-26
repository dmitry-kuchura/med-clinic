<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Firebird\PatientVisitRepository;
use App\Services\AppointmentService;
use Illuminate\Support\Carbon;

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
//        $result = $this->patientVisitRepository->getPatientVisit(68846);
//
//        $str = mb_convert_encoding(htmlspecialchars_decode($result[0]->data[0]->DATA), 'utf-8', 'windows-1251');
//        $xml = str_replace(['windows-1251', 'windows-1250'], 'utf-8', $str);
//        print_r($xml);

        $appointment = $this->getCurrentTime();
        $result = $this->appointmentService->getPatientsListForMessages($appointment);
    }

    private function getCurrentTime(): string
    {
        return Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }
}
