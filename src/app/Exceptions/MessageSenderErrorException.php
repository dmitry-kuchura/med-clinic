<?php

namespace App\Exceptions;

use RuntimeException;

class MessageSenderErrorException extends RuntimeException
{
    protected $message = 'Message can\'t sent!';
}
