<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $name
 * @property string $type
 * @property string $value
 * @property string $key
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Settings extends Model
{
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'type',
        'value',
        'key',
        'created_at',
        'updated_at',
    ];
}
