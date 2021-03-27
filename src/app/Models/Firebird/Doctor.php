<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $NR
 * @property int $HUMAN_NR
 *
 * @property Human $human
 */
class Doctor extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'STAFFS';

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
        return $this->hasOne('App\Models\Firebird\Human', 'NR', 'HUMAN_NR');
    }
}
