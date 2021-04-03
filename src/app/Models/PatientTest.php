<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $test_id
 * @property int $patient_id
 *
 * @property string $mark
 * @property string $result
 * @property string $reference_values
 * @property string $file
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Test $test
 * @property Patient $patient
 */
class PatientTest extends Model
{
    protected $table = 'patients_tests';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'test_id',
        'patient_id',
        'mark',
        'result',
        'reference_values',
        'file',
        'created_at',
        'updated_at',
    ];

    public function test()
    {
        return $this->hasOne('App\Models\Test', 'id', 'test_id');
    }

    public function patient()
    {
        return $this->hasOne('App\Models\Patient', 'id', 'patient_id');
    }
}
