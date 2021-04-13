<?php

namespace App\TurboSMS\Request;

class SendMessageRequest extends ApiRequest
{
    /** @var string */
    const METHOD = 'POST';

    /** @var string */
    const URL = 'message/send.json';

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

    private function build(): void
    {
        if ($this->data['type'] === 'sms') {
            $this->body['sms'] = [
                'sender' => $this->data['sender'],
                'text' => $this->data['text'],
            ];
        } else {
            $this->body['viber'] = [
                'sender' => $this->data['sender'],
                'text' => $this->data['text'],
            ];
        }

        foreach ($this->data['recipients'] as $recipient) {
            $this->body['recipients'][] = trim($recipient);
        }
    }
}
