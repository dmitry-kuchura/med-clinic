<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $appointment_at
 * @property boolean $is_mark
 * @property int $patient_id
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Patient $patient
 */
class PatientAppointmentReminder extends Model
{
    protected $table = 'patients_appointments_reminders';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'appointment_at',
        'is_mark',
        'patient_id',
        'created_at'
    ];

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }
}
