<?php

namespace App\TurboSMS;

use App\TurboSMS\Request\GetBalanceRequest;
use App\TurboSMS\Request\SendMessageRequest;
use App\TurboSMS\Response\ApiResponse;

class Service
{
    protected string $sender;

    private ApiCommunicator $communicator;

    public function __construct(TurboSMSConfig $config, ApiCommunicator $communicator)
    {
        $this->sender = $config->getSender();
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
