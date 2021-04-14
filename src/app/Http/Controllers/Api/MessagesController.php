<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MessagesService;
use Illuminate\Http\JsonResponse;

class MessagesController extends Controller
{
    private MessagesService $service;

    public function __construct(MessagesService $service)
    {
        $this->service = $service;
    }

    public function balance(): JsonResponse
    {
        $result = $this->service->getBalance();

        return $this->returnResponse(['result' => $result]);
    }
}
