<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $patient_id
 * @property int $doctor_id
 * @property string $appointment_at
 * @property string $comment
 * @property string $doctor_name
 * @property int $external_id
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Patient $patient
 * @property Doctor $doctor
 */
class PatientAppointment extends Model
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
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }

    public function doctor()
    {
        return $this->hasOne('App\Models\Doctor', 'id', 'doctor_id');
    }
}
