<?php

namespace App\Helpers;

use App\Exceptions\MessageSenderErrorException;
use App\Exceptions\SentMessageErrorException;
use Illuminate\Support\Facades\Http;

class TurboSMS
{
    protected Http $httpClient;

    protected string $secret;

    protected string $sender;

    protected string $baseUri = 'https://api.turbosms.ua';

    protected array $body = [];

    public function __construct()
    {
        $this->secret = config('sms.secret');
        $this->sender = config('sms.sender');
        $this->httpClient = new Http();
    }

    public function send(array $recipients, string $text, string $type = 'sms')
    {
        foreach ($recipients as $recipient) {
            $this->body['recipients'][] = trim($recipient);
        }

        $this->makeBody($text, $type);

        $url = $this->baseUri . '/message/send.json';

        $response = Http::timeout(3)
            ->retry(2, 200)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . trim($this->secret),
                'Content-Type' => 'application/json',
            ])->post($url, $this->body);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new SentMessageErrorException();
        }
    }

    public function balance()
    {
        $url = $this->baseUri . '/user/balance.json';

        $response = Http::timeout(3)
            ->retry(2, 200)
            ->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . trim($this->secret),
                'Content-Type' => 'application/json',
            ])->post($url, $this->body);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new MessageSenderErrorException();
        }
    }

    private function makeBody(string $text, string $type): void
    {
        if ($type === 'sms') {
            $this->body['sms'] = [
                'sender' => $this->sender,
                'text' => $text,
            ];
        } else {
            $this->body['viber'] = [
                'sender' => $this->sender,
                'text' => $text,
            ];
        }
    }
}
