<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $patient_id
 * @property string $file
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Patient $patient
 */
class PatientAnalysis extends Model
{
    protected $table = 'patients_analysis';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'patient_id',
        'file',
        'created_at',
        'updated_at',
    ];

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }
}
