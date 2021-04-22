<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $type
 * @property string $recipient
 * @property string $text
 * @property string $message_id
 * @property integer $response_code
 * @property string $response_status
 * @property string $status
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Message extends Model
{
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'type',
        'recipient',
        'text',
        'message_id',
        'response_code',
        'response_status',
        'status',
        'created_at',
        'updated_at',
    ];
}
