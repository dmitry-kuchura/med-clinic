<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $email
 * @property string $token
 * @property string $created_at
 */
class PasswordResets extends Model
{
    public $incrementing = false;

    protected $table = 'password_resets';

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
