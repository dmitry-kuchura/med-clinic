<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $last_appointment_at
 * @property int $count
 *
 * @property string $created_at
 * @property string $updated_at
 */
class MessagesJobs extends Model
{
    protected $table = 'messages_jobs';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'last_appointment_at',
        'count',
        'created_at',
        'updated_at',
    ];
}
