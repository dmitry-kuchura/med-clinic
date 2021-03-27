<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $NR
 * @property string $TIMESTART
 * @property string $TIMEEND
 * @property string $COMMENT
 * @property string $STAFFFIO
 * @property int $PAT_NR
 * @property string $PATNAME
 * @property int $OPTYPE_NR
 *
 * @property Patient $patient
 * @property Doctor $doctor
 */
class Appointment extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'APPOINTMENT_LOG';

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
        return $this->hasOne('App\Models\Firebird\Patient', 'NR', 'PAT_NR')->with('human');
    }

    public function doctor()
    {
        return $this->hasOne('App\Models\Firebird\Doctor', 'NR', 'STAFF_NR')->with('human');
    }
}
