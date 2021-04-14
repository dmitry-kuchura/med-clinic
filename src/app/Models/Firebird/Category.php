<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $NR
 * @property string $CATEGORYNAME
 */
class Category extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'DATACATEGORIES';

    /**
     * @var string
     */
    protected $primaryKey = 'NR';

    /**
     * @var bool
     */
    public $timestamps = false;
}
