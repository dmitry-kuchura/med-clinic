<?php

namespace App\Exceptions;

use RuntimeException;

class UpdateDoctorException extends RuntimeException
{
    protected $message = 'Doctor not updated!';
}
