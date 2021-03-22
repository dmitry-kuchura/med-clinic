<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * Class Patients
 *
 * @property int $NR
 * @property int $HUMAN_NR
 *
 * @property Humans $human
 */
class Patients extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'PATIENTS';

    /**
     * @var string
     */
    protected $primaryKey = 'NR';

    /**
     * @var bool
     */
    public $timestamps = false;

    public function human()
    {
        return $this->hasOne('App\Models\Firebird\Humans', 'NR', 'HUMAN_NR');
    }
}
