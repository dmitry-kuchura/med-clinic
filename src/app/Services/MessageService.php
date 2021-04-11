<?php

namespace App\Services;

use App\Exceptions\SentMessageErrorException;
use App\Helpers\TurboSMS;
use App\Models\MessageTemplate;
use App\Models\PatientAppointment;
use App\Models\PatientAppointmentReminder;
use App\Repositories\MessagesRepository;
use App\Repositories\MessagesTemplatesRepository;
use App\Repositories\PatientsMessagesRepository;
use Illuminate\Support\Carbon;
use Throwable;

class MessageService
{
    const RECORDS_AT_PAGE = 30;

    /** @var TurboSMS */
    private TurboSMS $smsSender;

    /** @var MessagesRepository */
    private MessagesRepository $messageRepository;

    /** @var PatientsMessagesRepository */
    private PatientsMessagesRepository $patientsMessagesRepository;

    /** @var MessagesTemplatesRepository */
    private MessagesTemplatesRepository $messageTemplatesRepository;

    public function __construct(
        MessagesRepository $messageRepository,
        PatientsMessagesRepository $patientsMessagesRepository,
        MessagesTemplatesRepository $messageTemplatesRepository
    )
    {
        $this->smsSender = new TurboSMS();
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

    public function send(array $request, array $response)
    {
        $result = $response['response_result'];

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
        $response = $this->smsSender->send([$request['phone']], $request['text']);

        $this->send($request, $response);
    }

    public function getBalance(): array
    {
        return $this->smsSender->balance();
    }

    public function listTemplates()
    {
        return $this->messageTemplatesRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function getMessageTemplate(int $id): ?MessageTemplate
    {
        return $this->messageTemplatesRepository->get($id);
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
            $template = $this->getMessageTemplate(1);

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
            $template = $this->getMessageTemplate(3);

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
