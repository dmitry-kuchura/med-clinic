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
 *
 * @property Patient $patient
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
}
