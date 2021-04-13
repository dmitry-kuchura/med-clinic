<?php

namespace App\TurboSMS\Request;

class GetBalanceRequest extends ApiRequest
{
    /** @var string */
    const METHOD = 'GET';

    /** @var string */
    const URL = 'user/balance.json';

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
}
