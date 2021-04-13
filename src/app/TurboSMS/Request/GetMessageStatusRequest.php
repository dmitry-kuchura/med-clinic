<?php

namespace App\TurboSMS\Request;

class GetMessageStatusRequest extends ApiRequest
{
    /** @var string */
    const METHOD = 'POST';

    /** @var string */
    const URL = 'message/status.json';

    private array $body = [];

    public function getMethod(): string
    {
        return self::METHOD;
    }

    public function getQueryUrl(): string
    {
        return self::URL;
    }

    public function getBody(): ?array
    {
        return null;
    }

    public function build()
    {

    }
}
