<?php

namespace App\Http\Controllers\Api;

use App\Console\Commands\SendSmsCommand;
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

    /** @var SendSmsCommand */
    private SendSmsCommand $command;

    public function __construct(
        SendSmsCommand $command,
        AppointmentService $appointmentService,
        PatientVisitRepository $patientVisitRepository
    )
    {
        $this->command = $command;
        $this->appointmentService = $appointmentService;
        $this->patientVisitRepository = $patientVisitRepository;
    }

    public function data()
    {
        $result = $this->patientVisitRepository->getPatientVisit(68846);
        $string = htmlspecialchars_decode($result[0]->data[0]->DATA);

        $pattern = '#<rvxml\s*>(.*?)</rvxml\s*>#is';
        preg_match($pattern, $string, $matches);

        $text = $matches[0];

        $text = str_replace(['<rvxml>', '</rvxml>'], '', $text);

        return response($text, 200, [
            'Content-Type' => 'application/html'
        ]);
    }

    public function list()
    {
        $this->command->handle();
    }
}
