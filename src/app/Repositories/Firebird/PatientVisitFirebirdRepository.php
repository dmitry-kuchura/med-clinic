<?php

namespace App\Repositories\Firebird;

use App\Models\Firebird\PatientVisit;
use Illuminate\Support\Collection;

class PatientVisitFirebirdRepository
{
    public function getPatientVisits(?int $external, ?string $timestamp): ?Collection
    {
        return PatientVisit::select(
            'PATIENTVISITS.NR',
            'PATIENTVISITS.PATIENT_NR',
            'PATIENTVISITS.DOCTOR_NR',
            'PATIENTVISITS.VISITDATE'
        )
            ->with('doctor', 'patient', 'data', 'data.template', 'data.category')
            ->where(function ($query) use ($external, $timestamp) {
                if (!$external) {
                    $query->where('PATIENTVISITS.VISITDATE', '>', $timestamp);
                }
            })
            ->where(function ($query) use ($external) {
                if ($external) {
                    $query->where('PATIENTVISITS.NR', '>', $external);
                }
            })
            ->limit(50)
            ->orderBy('PATIENTVISITS.VISITDATE', 'ASC')
            ->orderBy('PATIENTVISITS.NR', 'ASC')
            ->get();
    }

    public function getPatientVisitByExternalId(int $external): ?PatientVisit
    {
        return PatientVisit::select(
            'PATIENTVISITS.NR',
            'PATIENTVISITS.PATIENT_NR',
            'PATIENTVISITS.DOCTOR_NR',
            'PATIENTVISITS.VISITDATE'
        )
            ->with('doctor', 'patient', 'data', 'data.template', 'data.category')
            ->where('PATIENTVISITS.NR', '=', $external)
            ->first();
    }
}
