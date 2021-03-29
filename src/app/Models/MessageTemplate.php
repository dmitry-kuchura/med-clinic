<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $language
 * @property string $name
 * @property string $alias
 * @property string $content
 *
 * @property string $created_at
 * @property string $updated_at
 */
class MessageTemplate extends Model
{
    protected $table = 'messages_templates';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'language',
        'name',
        'alias',
        'content',
        'created_at',
        'updated_at',
    ];
}
