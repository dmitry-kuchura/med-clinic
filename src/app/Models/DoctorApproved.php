<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $doctor_id
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Doctor $doctor
 */
class DoctorApproved extends Model
{
    protected $table = 'doctors_approved';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'doctor_id',
        'created_at',
        'updated_at',
    ];

    public function doctor()
    {
        return $this->hasOne('App\Models\Doctor', 'id', 'doctor_id');
    }
}
