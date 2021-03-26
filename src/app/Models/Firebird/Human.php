<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $NR
 * @property string $SURNAME
 * @property string $FIRSTNAME
 * @property string $SECNAME
 * @property string $EMAIL
 * @property string $PHONE
 * @property string $MOBPHONE
 * @property string $FULLNAME
 * @property int $SEX
 * @property string $DOB
 */
class Human extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'HUMANS';

    /**
     * @var string
     */
    protected $primaryKey = 'NR';

    /**
     * @var bool
     */
    public $timestamps = false;
}
