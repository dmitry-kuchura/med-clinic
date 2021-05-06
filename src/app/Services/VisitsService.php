<?php

namespace App\Services;

use App\Models\PatientVisit as Visit;
use App\Models\Firebird\PatientVisit;
use App\Models\Firebird\PatientVisitData;
use App\Repositories\Firebird\PatientVisitFirebirdRepository;
use App\Repositories\PatientVisitDataRepository;
use App\Repositories\PatientVisitLateRepository;
use App\Repositories\PatientVisitRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class VisitsService
{
    /** @var PatientsService */
    private PatientsService $patientsService;

    /** @var DoctorsService */
    private DoctorsService $doctorsService;

    /** @var PatientVisitRepository */
    private PatientVisitRepository $patientVisitRepository;

    /** @var PatientVisitLateRepository */
    private PatientVisitLateRepository $patientVisitLateRepository;

    /** @var PatientVisitDataRepository */
    private PatientVisitDataRepository $patientVisitDataRepository;

    /** @var PatientVisitFirebirdRepository */
    private PatientVisitFirebirdRepository $patientVisitFirebirdRepository;

    public function __construct(
        PatientsService $patientsService,
        DoctorsService $doctorsService,
        PatientVisitRepository $patientVisitRepository,
        PatientVisitLateRepository $patientVisitLateRepository,
        PatientVisitDataRepository $patientVisitDataRepository,
        PatientVisitFirebirdRepository $patientVisitFirebirdRepository
    )
    {
        $this->patientsService = $patientsService;
        $this->doctorsService = $doctorsService;
        $this->patientVisitRepository = $patientVisitRepository;
        $this->patientVisitLateRepository = $patientVisitLateRepository;
        $this->patientVisitDataRepository = $patientVisitDataRepository;
        $this->patientVisitFirebirdRepository = $patientVisitFirebirdRepository;
    }

    public function getRemoteVisits(?int $external, ?string $timestamp): ?array
    {
        $patientVisits = [];

        $results = $this->patientVisitFirebirdRepository->getPatientVisits($external, $timestamp);

        /** @var PatientVisit $result */
        foreach ($results as $result) {
            $data = [];
            $visit = [];

            $data['visited_at'] = $result->VISITDATE;
            $data['external_id'] = $result->NR;
            $data['patient'] = [
                'first_name' => $result->patient->human->FIRSTNAME,
                'last_name' => $result->patient->human->SURNAME,
                'middle_name' => $result->patient->human->SECNAME,
                'gender' => $result->patient->human->SEX === 1 ? 'male' : 'female',
                'birthday' => $result->patient->human->DOB ?? null,
                'phone' => $result->patient->human->PHONE ?? $result->patient->human->MOBPHONE,
                'external_id' => $result->patient->NR,
            ];
            $data['doctor'] = [
                'first_name' => $result->doctor->human->FIRSTNAME,
                'last_name' => $result->doctor->human->SURNAME,
                'middle_name' => $result->doctor->human->SECNAME,
                'gender' => $result->doctor->human->SEX === 1 ? 'male' : 'female',
                'birthday' => $result->doctor->human->DOB ?? null,
                'phone' => $result->doctor->human->PHONE ?? $result->doctor->human->MOBPHONE,
                'external_id' => $result->doctor->NR,
            ];

            /** @var PatientVisitData $visitData */
            foreach ($result->data as $visitData) {
                $visit[] = [
                    'category' => $visitData->category->CATEGORYNAME,
                    'template' => $visitData->template->NAME,
                    'added_at' => $visitData->DATECHANGE
                ];

                $data['visit'] = $visit;
            }

            $patientVisits[] = $data;
        }

        return $patientVisits;
    }

    public function getRemoteVisitByExternalId(int $external): ?array
    {
        $result = $this->patientVisitFirebirdRepository->getPatientVisitByExternalId($external);

        return $this->prepareData($result);
    }

    public function getListForRemind(string $startTimestamp, string $endTimestamp): ?Collection
    {
        return $this->patientVisitRepository->getListForRemind($startTimestamp, $endTimestamp);
    }

    public function getPatientsVisitsList(int $id)
    {
        return $this->patientVisitRepository->getPatientsVisitsList($id);
    }

    public function store(array $data)
    {
        $patient = $this->patientsService->findPatientByExternalId($data['patient']['external_id']);
        if (!$patient) {
            $patient = $this->patientsService->syncPatient($data['patient']);
        }

        $doctor = $this->doctorsService->findDoctorByExternalId($data['doctor']['external_id']);
        if (!$doctor) {
            $doctor = $this->doctorsService->syncDoctor($data['doctor']);
        }

        $patientVisit = $this->patientVisitRepository->store([
            'visited_at' => $data['visited_at'],
            'external_id' => $data['external_id'],
            'patient_name' => $patient->first_name . ' ' . $patient->last_name,
            'doctor_name' => $doctor->first_name . ' ' . $doctor->last_name,
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
        ]);

        foreach ($data['visit'] as $visit) {
            $this->patientVisitDataRepository->store([
                'category' => $visit['category'] ?? null,
                'template' => $visit['template'] ?? null,
                'created_at' => $visit['added_at'] ?? Carbon::now()->format('Y-m-d H:i:s'),
                'visit_id' => $patientVisit->id,
            ]);
        }
    }

    public function late(array $data)
    {
        $this->patientVisitLateRepository->store(['external_id' => $data['external_id']]);
    }

    public function markedVisit(Visit $visit)
    {
        $this->patientVisitRepository->update(['is_marked' => true,], $visit->id);
    }

    public function getLastPatientsVisits(): ?Visit
    {
        return $this->patientVisitRepository->getLastVisits();
    }

    public function getLastLatePatientsVisits(): array
    {
        $result = [];

        $data = $this->patientVisitLateRepository->getLastVisits();

        foreach ($data as $item) {
            $result[] = $item->external_id;
        }

        return $result;
    }

    public function markedLatePatientVisit(int $externalId): void
    {
        $this->patientVisitLateRepository->markedLateVisit($externalId);
    }

    public function sync(array $data): void
    {
        if (isset($data['visit'])) {
            $this->store($data);
        } else {
            $this->late($data);
        }
    }

    private function prepareData(PatientVisit $result): ?array
    {
        $data = [];
        $visit = [];

        $data['visited_at'] = $result->VISITDATE;
        $data['external_id'] = $result->NR;
        $data['patient'] = [
            'first_name' => $result->patient->human->FIRSTNAME,
            'last_name' => $result->patient->human->SURNAME,
            'middle_name' => $result->patient->human->SECNAME,
            'gender' => $result->patient->human->SEX === 1 ? 'male' : 'female',
            'birthday' => $result->patient->human->DOB ?? null,
            'phone' => $result->patient->human->PHONE ?? $result->patient->human->MOBPHONE,
            'external_id' => $result->patient->NR,
        ];
        $data['doctor'] = [
            'first_name' => $result->doctor->human->FIRSTNAME,
            'last_name' => $result->doctor->human->SURNAME,
            'middle_name' => $result->doctor->human->SECNAME,
            'gender' => $result->doctor->human->SEX === 1 ? 'male' : 'female',
            'birthday' => $result->doctor->human->DOB ?? null,
            'phone' => $result->doctor->human->PHONE ?? $result->doctor->human->MOBPHONE,
            'external_id' => $result->doctor->NR,
        ];

        /** @var PatientVisitData $visitData */
        foreach ($result->data as $visitData) {
            $visit[] = [
                'category' => $visitData->category->CATEGORYNAME,
                'template' => $visitData->template->NAME,
                'added_at' => $visitData->DATECHANGE
            ];

            $data['visit'] = $visit;
        }

        return $data;
    }
}
