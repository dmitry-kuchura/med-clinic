<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $address
 * @property string $phone
 * @property string $birthday
 * @property string $gender
 * @property int $external_id
 * @property boolean $is_lab
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Doctor extends Model
{
    protected $table = 'doctors';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'phone',
        'birthday',
        'gender',
        'external_id',
        'is_lab',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
