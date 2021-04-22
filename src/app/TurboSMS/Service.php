<?php

namespace App\TurboSMS;

use App\TurboSMS\Request\GetBalanceRequest;
use App\TurboSMS\Request\GetMessageStatusRequest;
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

    /**
     * Метод get balance
     */
    public function getBalance(): ApiResponse
    {
        $request = new GetBalanceRequest();

        return $this->communicator->send($request);
    }

    /**
     * Метод add file
     */
    public function addFile(): ApiResponse
    {
        // TODO need implement return ApiResponse()
    }

    /**
     * Метод send message
     */
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

    /**
     * Метод message status
     */
    public function getMessageStatus(array $messages): ApiResponse
    {
        $request = new GetMessageStatusRequest([
            'messages' => $messages,
        ]);

        return $this->communicator->send($request);
    }
}
