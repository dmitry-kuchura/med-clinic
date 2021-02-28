<?php

namespace App\Actions;

use App\Repositories\MessagesRepository;
use App\Repositories\PatientsMessagesRepository;

class MessageAction
{
    const RECORDS_AT_PAGE = 10;

    private MessagesRepository $messageRepository;

    private PatientsMessagesRepository $patientsMessagesRepository;

    public function __construct(
        MessagesRepository $messageRepository,
        PatientsMessagesRepository $patientsMessagesRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->patientsMessagesRepository = $patientsMessagesRepository;
    }

    public function list(int $id)
    {
        return $this->patientsMessagesRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    public function send(array $request, array $response)
    {
        $responseResult = $response['response_result'];

        foreach ($responseResult as $value) {
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
}
