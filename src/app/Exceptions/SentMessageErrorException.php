<?php

namespace App\Exceptions;

use RuntimeException;

class SentMessageErrorException extends RuntimeException
{
    protected $message = 'Message can\'t sent!';
}
