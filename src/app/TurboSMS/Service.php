<?php

namespace App\TurboSMS;

use App\TurboSMS\Request\GetBalanceRequest;
use App\TurboSMS\Request\SendMessageRequest;
use App\TurboSMS\Response\ApiResponse;

class Service
{
    protected string $sender;

    private ApiCommunicator $communicator;

    public function __construct(ApiCommunicator $communicator)
    {
        $this->sender = config('sms.sender');
        $this->communicator = $communicator;
    }

    public function getBalance(): ApiResponse
    {
        $request = new GetBalanceRequest();

        return $this->communicator->send($request);
    }

    public function sendMessage(array $recipients, string $text, string $type = 'sms'): ApiResponse
    {
        $request = new SendMessageRequest([
            'recipients' => $recipients,
            'text' => $text,
            'type' => $type,
            'sender' => $this->sender,
        ]);

        return $this->communicator->send($request);
    }
}
