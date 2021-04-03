<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 *
 * @property string $name
 * @property float $cost
 * @property string $reference_value
 *
 * @property string $created_at
 * @property string $updated_at
 */
class Test extends Model
{
    protected $table = 'tests';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'cost',
        'reference_value',
        'created_at',
        'updated_at',
    ];
}
