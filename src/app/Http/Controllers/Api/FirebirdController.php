<?php

namespace App\Http\Controllers\Api;

use App\Actions\PatientAction;
use App\Http\Controllers\Controller;
use App\Models\Firebird\Appointment;
use App\Repositories\MessagesJobsRepository;
use App\Services\AppointmentService;
use Illuminate\Support\Carbon;

class FirebirdController extends Controller
{
    private PatientAction $patientAction;

    private AppointmentService $appointmentService;

    private MessagesJobsRepository $messagesJobsRepository;

    public function __construct(
        PatientAction $patientAction,
        AppointmentService $appointmentService,
        MessagesJobsRepository $messagesJobsRepository
    )
    {
        $this->patientAction = $patientAction;
        $this->appointmentService = $appointmentService;
        $this->messagesJobsRepository = $messagesJobsRepository;
    }

    public function list()
    {
        if ($this->checkIsAllowedSend()) {
            $lastAppointment = $this->appointmentService->getLastAppointmentPatient();

            if ($lastAppointment) {
                $result = $this->appointmentService->getPatientsListForMessages($lastAppointment);
            } else {
                $appointment = Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
                $result = $this->appointmentService->getPatientsListForMessages($appointment);
            }

//            foreach ($result as $record) {
//
//            }
        }
    }

    private function checkIsAllowedSend(): bool
    {
        $now = (int)Carbon::now('Europe/Kiev')->format('H');

        return $now > 06 && $now < 22;
    }
}
