<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\updateMessageTemplateRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PatientsMessagesController extends Controller
{
    private MessageService $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    public function send(updateMessageTemplateRequest $request): JsonResponse
    {
        $requestData = $request->all();
        $requestData['patient_id'] = (int)$request->route('id');

        $this->service->sendPatientMessage($requestData);

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function list(int $id): JsonResponse
    {
        $result = $this->service->getPatientMessageList($id);

        return $this->returnResponse(['result' => $result]);
    }
}
