<?php

namespace App\TurboSMS\Exception;

use RuntimeException;

class CommunicationErrorException extends RuntimeException
{
    protected $message = 'Error communication with TurboSMS!';
}
