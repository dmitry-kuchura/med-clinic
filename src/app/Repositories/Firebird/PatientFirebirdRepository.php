<?php

namespace App\Repositories\Firebird;

use App\Models\Firebird\Patient;
use Illuminate\Support\Collection;

class PatientFirebirdRepository
{
    public function getPatients(array $externalIds): ?Collection
    {
        return Patient::select(
            'PATIENTS.NR',
            'PATIENTS.HUMAN_NR',
        )
            ->with('human')
            ->whereIn('PATIENTS.NR', $externalIds)
            ->orderBy('PATIENTS.NR', 'DESC')
            ->get();
    }
}
