<?php

namespace App\Helpers;

use App\Repositories\Repository;
use Illuminate\Support\Str;

class GenerateTempEmail
{
    private Repository $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function generateTempEmail(): string
    {
        $temporaryEmail = 'temp_' . Str::random(10) . '@temporary.email';

        $doctor = $this->repository->findByEmail($temporaryEmail);

        if ($doctor) {
            return $this->generateTempEmail();
        }

        return $temporaryEmail;
    }
}
