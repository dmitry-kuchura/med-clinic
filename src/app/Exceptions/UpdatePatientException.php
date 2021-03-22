<?php

namespace App\Exceptions;

use RuntimeException;

class UpdatePatientException extends RuntimeException
{
    protected $message = 'Patient not updated!';
}
