<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessagesTemplates\UpdateMessageTemplateRequest;
use App\Services\MessagesService;
use Illuminate\Http\JsonResponse;

class MessagesTemplatesController extends Controller
{
    private MessagesService $service;

    public function __construct(MessagesService $service)
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

    public function update(UpdateMessageTemplateRequest $request): JsonResponse
    {
        $this->service->updateMessageTemplate($request->all());

        return $this->returnResponse(['updated' => true]);
    }
}
