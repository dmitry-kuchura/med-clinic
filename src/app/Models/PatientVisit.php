<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $patient_name
 * @property string $doctor_name
 * @property string $visited_at
 * @property string $comment
 * @property string $result
 * @property int $external_id
 * @property int $patient_id
 * @property int $doctor_id
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Patient $patient
 * @property Doctor $doctor
 * @property PatientVisitData $data
 */
class PatientVisit extends Model
{
    protected $table = 'patients_visits';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'patient_name',
        'doctor_name',
        'visited_at',
        'comment',
        'result',
        'external_id',
        'patient_id',
        'doctor_id',
        'created_at',
        'updated_at',
    ];

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }

    public function doctor()
    {
        return $this->hasOne('App\Models\Doctor', 'id', 'doctor_id');
    }

    public function data()
    {
        return $this->hasMany('App\Models\PatientVisitData', 'visit_id', 'id');
    }
}
