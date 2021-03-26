<?php

namespace App\Services;

use App\Facades\AppointmentFacade;
use App\Facades\MessageFacade;
use App\Facades\PatientFacade;
use App\Helpers\PhoneNumber;
use App\Helpers\TurboSMS;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientService
{
    /** @var PatientFacade */
    private PatientFacade $patientFacade;

    /** @var AppointmentFacade */
    private AppointmentFacade $appointmentFacade;

    /** @var MessageFacade */
    private MessageFacade $messageFacade;

    /** @var TurboSMS */
    private TurboSMS $smsSender;

    public function __construct(
        PatientFacade $patientFacade,
        AppointmentFacade $appointmentFacade,
        MessageFacade $messageFacade
    )
    {
        $this->smsSender = new TurboSMS();
        $this->patientFacade = $patientFacade;
        $this->appointmentFacade = $appointmentFacade;
        $this->messageFacade = $messageFacade;
    }

    public function list(): ?Collection
    {
        return $this->patientFacade->list();
    }

    public function find(int $id): ?Patient
    {
        return $this->patientFacade->find($id);
    }

    public function update(array $data): void
    {
        $this->patientFacade->update($data);
    }

    public function create(array $data): ?Patient
    {
        return $this->patientFacade->create($data);
    }

    public function sendMessage(array $request)
    {
        return true;

        $response = $this->smsSender->send([$request['phone']], $request['text']);
        $this->messageFacade->send($request, $response);
    }

    public function syncPatient(array $data): ?Patient
    {
        $data['phone'] = PhoneNumber::prepare($data['phone']);

        $patient = $this->patientFacade->create($data);

        if ($patient) {
            $data['patient_id'] = $patient->id;
            $this->appointmentFacade->create($data);
        }

        return $patient;
    }
}
