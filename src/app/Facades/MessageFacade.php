<?php

namespace App\Facades;

use App\Repositories\MessagesRepository;
use App\Repositories\PatientsMessagesRepository;

class MessageFacade implements Facade
{
    /** @var MessagesRepository */
    private MessagesRepository $messageRepository;

    /** @var PatientsMessagesRepository */
    private PatientsMessagesRepository $patientsMessagesRepository;

    public function __construct(
        MessagesRepository $messageRepository,
        PatientsMessagesRepository $patientsMessagesRepository
    )
    {
        $this->messageRepository = $messageRepository;
        $this->patientsMessagesRepository = $patientsMessagesRepository;
    }

    public function paginate(int $id)
    {
        return $this->patientsMessagesRepository->paginate($id, self::RECORDS_AT_PAGE);
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }

    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    public function send(array $request, array $data): void
    {
        $responseResult = $data['response_result'];

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
