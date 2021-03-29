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

    public function list(): JsonResponse
    {
        $result = $this->service->listTemplates();

        return $this->returnResponse(['result' => $result]);
    }

    public function info(int $id): JsonResponse
    {
        $result = $this->service->getMessageTemplate($id);

        return $this->returnResponse(['result' => $result]);
    }
}
