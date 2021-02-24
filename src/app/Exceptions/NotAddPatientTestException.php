<?php

namespace App\Exceptions;

use RuntimeException;

class NotAddPatientTestException extends RuntimeException
{
    protected $message = 'Patient test not added!';
}
