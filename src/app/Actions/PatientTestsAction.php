<?php

namespace App\Actions;

use App\Exceptions\NotAddPatientTestException;
use App\Models\PatientTest;
use App\Repositories\PatientsTestsRepository;

class PatientTestsAction
{
    const RECORDS_AT_PAGE = 10;

    private PatientsTestsRepository $patientsTestsRepository;

    public function __construct(PatientsTestsRepository $patientsTestsRepository)
    {
        $this->patientsTestsRepository = $patientsTestsRepository;
    }

    public function addPatientTest(array $data): PatientTest
    {
        $testData = [
            'test_id' => $data['test_id'],
            'patient_id' => $data['patient_id'],
            'file' => $data['file'] ?? null,
            'result' => $data['result'] ?? 'Результат у файлі',
            'reference_values' => $data['reference_values'] ?? null,
        ];

        try {
            $patientTest = $this->patientsTestsRepository->store($testData);
        } catch (\Throwable $e) {
            throw new NotAddPatientTestException($e->getMessage());
        }

        return $this->patientsTestsRepository->get($patientTest->id);
    }

    public function listPatientTests(int $id)
    {
        return $this->patientsTestsRepository->paginate($id, self::RECORDS_AT_PAGE);
    }
}
