<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $external_id
 * @property bool $is_marked
 *
 * @property string $created_at
 * @property string $updated_at
 */
class PatientVisitLate extends Model
{
    protected $table = 'patients_visits_late';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'external_id',
        'is_marked',
        'created_at',
        'updated_at',
    ];
}
