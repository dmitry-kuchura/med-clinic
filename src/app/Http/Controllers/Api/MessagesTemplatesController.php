<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;

class MessagesTemplatesController extends Controller
{
    private MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->list($id);

        return $this->returnResponse(['result' => $result]);
    }
}
