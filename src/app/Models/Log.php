<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $message
 * @property string $context
 * @property string $level
 * @property string $request_headers
 * @property string $request
 * @property string $response_headers
 * @property string $response
 * @property string $remote_addr
 * @property string $user_agent
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Log extends Model
{
    protected $table = 'logs';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'message',
        'context',
        'level',
        'request_headers',
        'request',
        'response_headers',
        'response',
        'remote_addr',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
