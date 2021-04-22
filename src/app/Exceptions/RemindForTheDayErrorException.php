<?php

namespace App\Exceptions;

use RuntimeException;

class RemindForTheDayErrorException extends RuntimeException
{
    protected $message = 'Error of remind!';
}
