<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * Class AppointmentLog
 *
 * @property int $NR
 * @property string $TIMESTART
 * @property string $TIMEEND
 * @property string $COMMENT
 * @property int $PAT_NR
 * @property string $PATNAME
 *
 * @property Patients $patient
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
        return $this->hasOne('App\Models\Firebird\Patients', 'NR', 'PAT_NR')->with('human');
    }
}
