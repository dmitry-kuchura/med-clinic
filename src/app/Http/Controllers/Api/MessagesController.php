<?php

namespace App\Http\Controllers\Api;

use App\Actions\MessageAction;
use App\Helpers\TurboSMS;
use App\Http\Controllers\Controller;
use App\Http\Requests\Messages\SendMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessagesController extends Controller
{
    private TurboSMS $service;

    private MessageAction $action;

    public function __construct(MessageAction $action)
    {
        $this->service = new TurboSMS();
        $this->action = $action;
    }

    public function send(SendMessageRequest $request)
    {
        $requestData = $request->all();
        $requestData['patient_id'] = (int)$request->route('id');

        $response = $this->service->send([$requestData['phone']], $requestData['text']);

        $this->action->send($requestData, $response);

        return $this->returnResponse(['created' => true], Response::HTTP_CREATED);
    }

    public function list(Request $request)
    {
        dd('here');
    }
}
