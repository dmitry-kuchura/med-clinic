<?php

namespace App\Models;

use App\Models\Enum\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 *
 * @property int $user_id
 * @property string $email
 * @property string $token
 * @property string $role
 * @property string $expired_at
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property bool $isAdmin
 * @property UsersTokens $tokens
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->hasMany('App\Models\UsersTokens', 'id', 'user_id');
    }

    public function isAdmin()
    {
        return in_array($this->role, [UserRole::ADMIN, UserRole::SUPER_ADMIN], true);
    }

    public function isSuperAdmin()
    {
        return in_array($this->role, [UserRole::SUPER_ADMIN], true);
    }
}
