<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $template
 *
 * @property string $created_at
 * @property string $updated_at
 */
class PatientVisitTemplate extends Model
{
    protected $table = 'patients_visits_templates';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'template',
        'created_at',
        'updated_at',
    ];
}
