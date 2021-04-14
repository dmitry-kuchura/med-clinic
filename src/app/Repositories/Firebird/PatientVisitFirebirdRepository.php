<?php

namespace App\Repositories\Firebird;

use App\Models\Firebird\PatientVisit;
use Illuminate\Support\Collection;

class PatientVisitFirebirdRepository
{
    public function getPatientVisit(?int $external): ?Collection
    {
        return PatientVisit::select(
            'PATIENTVISITS.NR',
            'PATIENTVISITS.PATIENT_NR',
            'PATIENTVISITS.DOCTOR_NR',
            'PATIENTVISITS.VISITDATE'
        )
            ->with('doctor', 'patient', 'data', 'data.template', 'data.category')
            ->where('PATIENTVISITS.NR', $external)
            ->limit(5)
            ->orderBy('PATIENTVISITS.VISITDATE', 'DESC')
            ->orderBy('PATIENTVISITS.NR', 'DESC')
            ->get();
    }
}
