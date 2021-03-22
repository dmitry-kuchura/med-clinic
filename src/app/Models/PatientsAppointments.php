<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $patient_id
 * @property string $appointment_at
 * @property string $comment
 * @property string $doctor_name
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Patients $patient
 */
class PatientsAppointments extends Model
{
    protected $table = 'patients_appointments';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'patient_id',
        'appointment_at',
        'comment',
        'doctor_name',
        'created_at',
        'updated_at',
    ];

    public function patient()
    {
        return $this->hasOne('App\Models\Patients', 'id', 'patient_id');
    }
}
