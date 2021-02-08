<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $phone
 * @property string $birthday
 * @property string $gender
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Patients extends Model
{
    protected $table = 'patients';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'address',
        'phone',
        'birthday',
        'gender',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
