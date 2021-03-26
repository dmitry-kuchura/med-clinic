<?php

namespace App\Services;

use App\Facades\MessageFacade;
use App\Helpers\TurboSMS;

class MessageService
{
    /** @var TurboSMS */
    private TurboSMS $smsSender;

    /** @var MessageFacade */
    private MessageFacade $messageFacade;

    public function __construct(MessageFacade $messageFacade)
    {
        $this->smsSender = new TurboSMS();
        $this->messageFacade = $messageFacade;
    }

    public function list(int $id)
    {
        return $this->messageFacade->paginate($id);
    }

    public function send(array $request, array $response)
    {
        $this->messageFacade->send($request, $response);
    }

    public function sendMessage(array $request)
    {
        return true;

        $response = $this->smsSender->send([$request['phone']], $request['text']);

        $this->send($request, $response);
    }
}
