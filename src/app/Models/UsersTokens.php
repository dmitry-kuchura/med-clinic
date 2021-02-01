<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property int $user_id
 * @property string $token
 * @property string $expired_at
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class UsersTokens extends Model
{
    protected $table = 'users_tokens';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'token',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
