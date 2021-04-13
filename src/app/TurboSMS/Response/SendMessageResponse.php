<?php

namespace App\TurboSMS\Response;

class SendMessageResponse extends ApiResponse
{
    public function getResponseCode(): int
    {
        return $this->response['response_code'];
    }

    public function getResponseStatus(): string
    {
        return $this->response['response_status'];
    }

    public function getResponseResult(): ?array
    {
        return $this->response['response_result'];
    }
}
