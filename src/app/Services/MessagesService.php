<?php

namespace App\Services;

use App\Exceptions\SentMessageErrorException;
use App\Models\MessageTemplate;
use App\Models\PatientAppointment;
use App\Models\PatientAppointmentReminder;
use App\Repositories\MessagesRepository;
use App\Repositories\MessagesTemplatesRepository;
use App\Repositories\PatientsMessagesRepository;
use App\TurboSMS\Response\ApiResponse;
use App\TurboSMS\Service;
use Illuminate\Support\Carbon;
use Throwable;

class MessagesService
{
    const RECORDS_AT_PAGE = 30;

    /** @var Service */
    private Service $smsSender;

    /** @var MessagesRepository */
    private MessagesRepository $messageRepository;

    /** @var PatientsMessagesRepository */
    private PatientsMessagesRepository $patientsMessagesRepository;

    /** @var MessagesTemplatesRepository */
    private MessagesTemplatesRepository $messageTemplatesRepository;

    public function __construct(
        Service $smsSender,
        MessagesRepository $messageRepository,
        PatientsMessagesRepository $patientsMessagesRepository,
        MessagesTemplatesRepository $messageTemplatesRepository
    )
    {
        $this->smsSender = $smsSender;
        $this->messageRepository = $messageRepository;
        $this->patientsMessagesRepository = $patientsMessagesRepository;
        $this->messageTemplatesRepository = $messageTemplatesRepository;
    }

    public function getPatientMessageList(int $id)
    {
        return $this->patientsMessagesRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    public function update(array $data)
    {
        $this->messageTemplatesRepository->update($data, $data['id']);
    }

    public function send(array $request, ApiResponse $response)
    {
        $result = $response->getResponseResult();

        foreach ($result as $value) {
            $messageData = [
                'type' => 'sms',
                'text' => $request['text'],
                'recipient' => '+' . $value['phone'],
                'message_id' => $value['message_id'],
                'response_code' => $value['response_code'],
                'response_status' => $value['response_status']
            ];

            $message = $this->messageRepository->store($messageData);

            if ($request['patient_id']) {
                $this->patientsMessagesRepository->store([
                    'patient_id' => $request['patient_id'],
                    'message_id' => $message->id
                ]);
            }
        }
    }

    public function sendPatientMessage(array $request)
    {
        $response = $this->smsSender->sendMessage([$request['phone']], $request['text']);

        $this->send($request, $response);
    }

    public function getBalance(): float
    {
        $response = $this->smsSender->getBalance();

        return $response->getBalance();
    }

    public function listTemplates()
    {
        return $this->messageTemplatesRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function getMessageTemplate(int $id): ?MessageTemplate
    {
        return $this->messageTemplatesRepository->get($id);
    }

    public function getMessageTemplateByAlias(string $alias): ?MessageTemplate
    {
        return $this->messageTemplatesRepository->find($alias);
    }

    public function sendMessageReminder(array $request): void
    {
//        $response = $this->smsSender->send([$request['phone']], $request['text']);
//        $this->send($request, $response);
    }

    public function remindBeforeDay(PatientAppointment $patientAppointment)
    {
        $request = [];

        try {
            if ($patientAppointment->doctor->is_lab) {
                $template = $this->getMessageTemplateByAlias('patient-appointment-lab');
            } else {
                $template = $this->getMessageTemplateByAlias('patient-appointment');
            }

            $datetime = Carbon::parse($patientAppointment->appointment_at);

            $text = str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $template);

            $request['phone'] = '+380931106215';
            $request['text'] = $text;

            $this->sendMessageReminder($request);
        } catch (Throwable $throwable) {
            throw new SentMessageErrorException($throwable->getMessage());
        }
    }

    public function remindDayOnDay(PatientAppointmentReminder $patientAppointmentReminder)
    {
        $request = [];

        try {
            $template = $this->getMessageTemplateByAlias('patient-appointment-reminder');

            $datetime = Carbon::parse($patientAppointmentReminder->appointment_at);

            $text = str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $template);

            $request['phone'] = '+380931106215';
            $request['text'] = $text;

            $this->sendMessageReminder($request);
        } catch (Throwable $throwable) {
            throw new SentMessageErrorException($throwable->getMessage());
        }
    }
}
