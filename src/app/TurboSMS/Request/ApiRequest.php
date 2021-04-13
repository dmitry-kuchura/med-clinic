<?php

namespace App\TurboSMS\Request;

abstract class ApiRequest
{
    abstract public function getMethod(): string;

    abstract public function getQueryUrl(): string;

    abstract public function getBody(): ?array;
}
