<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MessagesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    private MessagesService $service;

    public function __construct(MessagesService $service)
    {
        $this->service = $service;
    }

    public function list(Request $request): JsonResponse
    {
        $phone = $request->get('phone') ?? null;
        $status = $request->get('status') ?? null;

        $result = $this->service->list($phone, $status);

        return $this->returnResponse(['result' => $result]);
    }

    public function balance(): JsonResponse
    {
        $result = $this->service->getBalance();

        return $this->returnResponse(['result' => $result]);
    }
}
