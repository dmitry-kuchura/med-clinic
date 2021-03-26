<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $message_id
 * @property int $patient_id
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Message $message
 * @property Patient $patient
 */
class PatientsMessages extends Model
{
    protected $table = 'patients_messages';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'message_id',
        'patient_id',
        'created_at',
        'updated_at',
    ];

    public function message()
    {
        return $this->hasOne('App\Models\Message', 'id', 'message_id');
    }

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }
}
