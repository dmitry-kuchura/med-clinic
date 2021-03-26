<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $NR
 * @property int $PATIENT_NR
 * @property string $VISITDATE
 * @property int $RECORDACTIVE
 *
 * @property Patient $patient
 * @property PatientVisitData $data
 */
class PatientVisit extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'PATIENTVISITS';

    /**
     * @var string
     */
    protected $primaryKey = 'NR';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function patient()
    {
        return $this->hasOne('App\Models\Firebird\Patient', 'NR', 'PATIENT_NR')->with('human');
    }

    public function data()
    {
        return $this->hasMany('App\Models\Firebird\PatientVisitData', 'VISIT_NR', 'NR');
    }
}
