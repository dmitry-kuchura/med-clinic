<?php

namespace App\TurboSMS\Response;

abstract class ApiResponse
{
    public array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    abstract public function getResponseCode(): int;

    abstract public function getResponseStatus(): string;

    abstract public function getResponseResult(): ?array;
}
