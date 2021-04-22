<?php

namespace App\Exceptions;

use RuntimeException;

class UpdateMessageErrorException extends RuntimeException
{
    protected $message = 'Error update message!';
}
