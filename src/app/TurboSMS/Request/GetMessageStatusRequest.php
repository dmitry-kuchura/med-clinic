<?php

namespace App\TurboSMS\Request;

class GetMessageStatusRequest extends ApiRequest
{
    /** @var string */
    const METHOD = 'POST';

    /** @var string */
    const URL = 'message/status.json';

    /** @var array */
    private array $body;

    /** @var array */
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->build();
    }

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
        return $this->body;
    }

    public function build()
    {
        foreach ($this->data['messages'] as $message) {
            $this->body['messages'][] = trim($message);
        }
    }
}
