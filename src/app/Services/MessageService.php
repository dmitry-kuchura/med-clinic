<?php

namespace App\Services;

use App\Facades\MessageFacade;
use App\Facades\MessageTemplatesFacade;
use App\Helpers\TurboSMS;
use App\Models\PatientAppointment;

class MessageService
{
    /** @var TurboSMS */
    private TurboSMS $smsSender;

    /** @var MessageFacade */
    private MessageFacade $messageFacade;

    /** @var MessageTemplatesFacade */
    private MessageTemplatesFacade $messageTemplatesFacade;

    public function __construct(
        MessageFacade $messageFacade,
        MessageTemplatesFacade $messageTemplatesFacade
    )
    {
        $this->smsSender = new TurboSMS();
        $this->messageFacade = $messageFacade;
        $this->messageTemplatesFacade = $messageTemplatesFacade;
    }

    public function getPatientMessageList(int $id)
    {
        return $this->messageFacade->paginate($id);
    }

    public function send(array $request, array $response)
    {
        $this->messageFacade->send($request, $response);
    }

    public function sendPatientMessage(array $request)
    {
        $response = $this->smsSender->send([$request['phone']], $request['text']);

        $this->send($request, $response);
    }

    public function getBalance(): array
    {
        return $this->smsSender->balance();
    }

    public function listTemplates()
    {
        return $this->messageTemplatesFacade->paginate();
    }

    public function getMessageTemplate(int $id)
    {
        return $this->messageTemplatesFacade->find($id);
    }

    public function remindBeforeDay(PatientAppointment $patientAppointment)
    {
//        $response = $this->smsSender->send([$request['phone']], $request['text']);
//        $this->send($request, $response);
    }
}
