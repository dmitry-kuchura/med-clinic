<?php

namespace App\Exceptions;

use RuntimeException;

class FileUploadErrorException extends RuntimeException
{
    protected $message = 'File upload error!';
}
