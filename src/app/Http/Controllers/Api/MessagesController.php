<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\SendMessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MessagesController extends Controller
{
    private MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function send(SendMessageRequest $request): JsonResponse
    {
        $requestData = $request->all();
        $requestData['patient_id'] = (int)$request->route('id');

        $this->service->sendMessage($requestData);

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->list($id);

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }

    public function balance(): JsonResponse
    {
        $result = $this->service->getBalance();

        return $this->returnResponse(['result' => $result], Response::HTTP_OK);
    }
}
