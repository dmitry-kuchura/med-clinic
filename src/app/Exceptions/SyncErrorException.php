<?php

namespace App\Exceptions;

use RuntimeException;

class SyncErrorException extends RuntimeException
{
    protected $message = 'Error sync!';
}
