<?php

namespace App\Services;

use App\Exceptions\NotAddPatientTestException;
use App\Models\PatientAnalysis;
use App\Repositories\PatientsAnalysisRepository;

class AnalysisService
{
    const RECORDS_AT_PAGE = 30;

    private PatientsAnalysisRepository $patientsAnalysisRepository;

    public function __construct(
        PatientsAnalysisRepository $patientsTestsRepository
    )
    {
        $this->patientsAnalysisRepository = $patientsTestsRepository;
    }

    public function list(int $id)
    {
        return $this->patientsAnalysisRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    public function create(array $data): PatientAnalysis
    {
        $testData = [
            'patient_id' => $data['patient_id'],
            'file' => $data['file'] ?? null,
        ];

        try {
            $patientTest = $this->patientsAnalysisRepository->store($testData);
        } catch (\Throwable $e) {
            throw new NotAddPatientTestException($e->getMessage());
        }

        return $this->patientsAnalysisRepository->get($patientTest->id);
    }
}
