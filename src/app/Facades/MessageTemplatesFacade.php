<?php

namespace App\Facades;

use App\Repositories\MessagesTemplatesRepository;

class MessageTemplatesFacade implements Facade
{
    /** @var MessagesTemplatesRepository */
    private MessagesTemplatesRepository $messageTemplatesRepository;

    public function __construct(
        MessagesTemplatesRepository $messageTemplatesRepository
    )
    {
        $this->messageTemplatesRepository = $messageTemplatesRepository;
    }

    public function paginate()
    {
        return $this->messageTemplatesRepository->paginate(self::RECORDS_AT_PAGE);
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
