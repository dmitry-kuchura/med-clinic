<?php

namespace App\Services;

use App\Exceptions\SentMessageErrorException;
use App\Helpers\Date;
use App\Models\Message;
use App\Models\MessageTemplate;
use App\Models\PatientAppointment;
use App\Models\PatientAppointmentReminder;
use App\Models\PatientVisit;
use App\Repositories\MessagesRepository;
use App\Repositories\MessagesTemplatesRepository;
use App\Repositories\PatientsMessagesRepository;
use App\TurboSMS\Response\ApiResponse;
use App\TurboSMS\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
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

    public function updateMessageTemplate(array $data)
    {
        $this->messageTemplatesRepository->update($data, $data['id']);
    }

    public function updateMessage(array $response)
    {
        foreach ($response as $message) {
            if ((int)$message['response_code'] === 0)
            $this->messageRepository->updateMessage([
                'message_id' => $message['message_id'],
                'status' => $message['status'],
                'response_code' => $message['response_code'],
                'response_status' => $message['response_status'],
            ]);
        }
    }

    public function saveMessage(array $request, ApiResponse $response)
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
                $this->savePatientMessage([
                    'patient_id' => $request['patient_id'],
                    'message_id' => $message->id
                ]);
            }
        }
    }

    public function savePatientMessage(array $data)
    {
        $this->patientsMessagesRepository->store($data);
    }

    public function sendPatientMessage(array $request)
    {
        $response = $this->smsSender->sendMessage([$request['phone']], $request['text']);

        $this->saveMessage($request, $response);
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
        if (config('sms.allowed')) {
            if (!App::environment('production')) {
                $request['phone'] = '+380931106215';
            }

            $response = $this->smsSender->sendMessage([$request['phone']], $request['text']);
            $this->saveMessage($request, $response);
        }
    }

    public function getMessagesStatus(?array $messagesIds): void
    {
        $response = $this->smsSender->getMessageStatus($messagesIds);

        if ($response->getResponseResult()) {
            $this->updateMessage($response->getResponseResult());
        }
    }

    public function alreadyRemindToday(string $phone): bool
    {
        $today = Date::getMorningTime();

        $result = $this->messageRepository->find($phone, $today);

        if (!$result) {
            return false;
        }

        return count($result) > 2;
    }

    public function remindNewAnalyse(PatientVisit $visit)
    {
        $request = [];

        try {
            $template = $this->getMessageTemplateByAlias('patient-reminder-analyse');

            $request['phone'] = $visit->patient->phone;
            $request['patient_id'] = $visit->patient->id;
            $request['text'] = $template->content;

            $this->sendMessageReminder($request);
        } catch (Throwable $throwable) {
            throw new SentMessageErrorException($throwable->getMessage());
        }
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

            $text = str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $template->content);

            $request['phone'] = $patientAppointment->patient->phone;
            $request['text'] = $text;
            $request['patient_id'] = $patientAppointment->patient->id;

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

            $text = str_replace(['{date}', '{time}'], [$datetime->format('d.m.y'), $datetime->format('H:i')], $template->content);

            $request['phone'] = $patientAppointmentReminder->patient->phone;
            $request['text'] = $text;
            $request['patient_id'] = $patientAppointmentReminder->patient->id;

            $this->sendMessageReminder($request);
        } catch (Throwable $throwable) {
            throw new SentMessageErrorException($throwable->getMessage());
        }
    }

    public function getMessagesForUpdateStatus(): ?array
    {
        $data = [];

        $messages = $this->messageRepository->messagesForUpdateStatus();

        /** @var Message $message */
        foreach ($messages as $message) {
            $data[] = $message->message_id;
        }

        return $data;
    }
}
