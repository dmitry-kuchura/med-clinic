<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $category
 * @property string $template
 * @property string $data
 * @property int $visit_id
 *
 * @property string $created_at
 * @property string $updated_at
 */
class PatientVisitData extends Model
{
    protected $table = 'patients_visits_data';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'category',
        'template',
        'data',
        'visit_id',
        'created_at',
        'updated_at',
    ];
}
