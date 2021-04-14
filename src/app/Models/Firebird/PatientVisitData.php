<?php

namespace App\Models\Firebird;

use Firebird\Eloquent\Model;

/**
 * @property int $VISIT_NR
 * @property int $CATEGORY_NR
 * @property int $ORDERNR
 * @property int $TEMPLATE_NR
 * @property string $DATA
 * @property string $DATECHANGE
 * @property string $PERMISSIONDATA
 *
 * @property Template $template
 * @property Category $category
 */
class PatientVisitData extends Model
{
    /**
     * @var string
     */
    protected $connection = 'firebird';

    /**
     * @var string
     */
    protected $table = 'PATIENTVISITDATA';

    /**
     * @var string
     */
    protected $primaryKey = ['VISIT_NR', 'CATEGORY_NR', 'ORDERNR'];

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    public function template()
    {
        return $this->hasOne('App\Models\Firebird\Template', 'NR', 'TEMPLATE_NR');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Firebird\Category', 'NR', 'CATEGORY_NR');
    }
}
