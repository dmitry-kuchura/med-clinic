<?php

namespace App\TurboSMS;

class TurboSMSConfig
{
    /** @var string */
    public string $sender;

    /** @var string */
    public string $secret;

    /** @var bool */
    public bool $allowed;

    /** @var string */
    public string $baseUri = 'https://api.turbosms.ua/';

    public function __construct()
    {
        $this->sender = config('sms.sender');
        $this->secret = config('sms.secret');
        $this->allowed = (bool)config('sms.allowed');
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function isAllowed(): bool
    {
        return $this->allowed;
    }
}
