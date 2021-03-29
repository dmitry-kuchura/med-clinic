<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;

class MessagesController extends Controller
{
    private MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function balance(): JsonResponse
    {
        $result = $this->service->getBalance();

        return $this->returnResponse(['result' => $result]);
    }
}
